import { useCallback, useEffect, useRef } from 'react';

type Props = {
    src: string;
    alt: string;
};

type ActiveRipple = {
    x: number; // percentage, relative to the container
    y: number; // percentage, relative to the container
    startedAt: number;
};

// Slow, dreamy timings — "reveals slowly, then eases back into the blur
// slowly", per the client's brief.
const GROW_MS = 1400;
const HOLD_MS = 500;
const SHRINK_MS = 1800;
const TOTAL_MS = GROW_MS + HOLD_MS + SHRINK_MS;
const MAX_RADIUS_PX = 120;

function easeOutCubic(t: number): number {
    return 1 - Math.pow(1 - t, 3);
}

function easeInCubic(t: number): number {
    return t * t * t;
}

/** Grows slowly, holds briefly at full spread, then eases back into the blur. */
function radiusAtElapsed(elapsedMs: number): number {
    if (elapsedMs < GROW_MS) {
        return MAX_RADIUS_PX * easeOutCubic(elapsedMs / GROW_MS);
    }
    if (elapsedMs < GROW_MS + HOLD_MS) {
        return MAX_RADIUS_PX;
    }
    if (elapsedMs < TOTAL_MS) {
        const t = (elapsedMs - GROW_MS - HOLD_MS) / SHRINK_MS;
        return MAX_RADIUS_PX * (1 - easeInCubic(t));
    }
    return 0;
}

/**
 * Three soft, feathered blobs orbiting the click point at different speeds
 * and phases — instead of one crisp circle — so the reveal's edge wobbles
 * and undulates like spreading water rather than expanding uniformly. The
 * wide black→transparent fade (35% to 100%) is what gives it the hazy,
 * "fogging in" edge instead of a hard cutout.
 */
function rippleMask(ripple: ActiveRipple, elapsedMs: number): string | null {
    const radius = radiusAtElapsed(elapsedMs);

    if (radius <= 1) {
        return null;
    }

    const t = elapsedMs / 1000;
    const blobs = [
        { dx: 18 * Math.sin(t * 1.3), dy: 14 * Math.cos(t * 1.1), r: radius },
        {
            dx: 16 * Math.cos(t * 0.9 + 2),
            dy: 18 * Math.sin(t * 1.4 + 2),
            r: radius * 0.85,
        },
        {
            dx: 20 * Math.sin(t * 1.6 + 4),
            dy: 12 * Math.cos(t * 0.8 + 4),
            r: radius * 0.92,
        },
    ];

    return blobs
        .map(
            (blob) =>
                `radial-gradient(circle ${blob.r}px at calc(${ripple.x}% + ${blob.dx}px) calc(${ripple.y}% + ${blob.dy}px), black 35%, transparent 100%)`,
        )
        .join(', ');
}

/**
 * The cover starts softly blurred/dimmed (an elegant "out of focus" look).
 * Two independent ways to bring it into focus:
 *  - Hovering follows the sharp photo under the cursor (desktop only).
 *  - Tapping/clicking spreads a slow, wobbly "water ripple" reveal from that
 *    point, which lingers and then fades back into the blur. Multiple taps
 *    ripple independently and combine.
 */
export function CoverImage({ src, alt }: Props) {
    const hoverOverlayRef = useRef<HTMLImageElement>(null);
    const rippleOverlayRef = useRef<HTMLImageElement>(null);
    const ripplesRef = useRef<Map<number, ActiveRipple>>(new Map());
    const rafRef = useRef<number | null>(null);
    const nextId = useRef(0);

    const tick = useCallback(() => {
        const now = performance.now();
        const ripples = ripplesRef.current;
        const masks: string[] = [];

        for (const [id, ripple] of ripples) {
            const elapsed = now - ripple.startedAt;

            if (elapsed >= TOTAL_MS) {
                ripples.delete(id);
                continue;
            }

            const mask = rippleMask(ripple, elapsed);
            if (mask) {
                masks.push(mask);
            }
        }

        const overlay = rippleOverlayRef.current;
        if (overlay) {
            if (masks.length > 0) {
                const maskValue = masks.join(', ');
                overlay.style.opacity = '1';
                overlay.style.maskImage = maskValue;
                overlay.style.setProperty('-webkit-mask-image', maskValue);
            } else {
                overlay.style.opacity = '0';
            }
        }

        rafRef.current = ripples.size > 0 ? requestAnimationFrame(tick) : null;
    }, []);

    useEffect(() => {
        return () => {
            if (rafRef.current !== null) {
                cancelAnimationFrame(rafRef.current);
            }
        };
    }, []);

    const handleMouseMove = (event: React.MouseEvent<HTMLDivElement>) => {
        const rect = event.currentTarget.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width) * 100;
        const y = ((event.clientY - rect.top) / rect.height) * 100;

        hoverOverlayRef.current?.style.setProperty('--reveal-x', `${x}%`);
        hoverOverlayRef.current?.style.setProperty('--reveal-y', `${y}%`);
    };

    const handleClick = (event: React.MouseEvent<HTMLDivElement>) => {
        const rect = event.currentTarget.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width) * 100;
        const y = ((event.clientY - rect.top) / rect.height) * 100;

        ripplesRef.current.set(nextId.current++, {
            x,
            y,
            startedAt: performance.now(),
        });

        if (rafRef.current === null) {
            rafRef.current = requestAnimationFrame(tick);
        }
    };

    const hoverMask = [
        'radial-gradient(circle 230px at calc(var(--reveal-x, 50%) - 25px) calc(var(--reveal-y, 50%) + 20px), black 45%, transparent 100%)',
        'radial-gradient(circle 190px at calc(var(--reveal-x, 50%) + 30px) calc(var(--reveal-y, 50%) - 15px), black 45%, transparent 100%)',
        'radial-gradient(circle 200px at var(--reveal-x, 50%) var(--reveal-y, 50%), black 45%, transparent 100%)',
    ].join(', ');

    return (
        <div
            className="group relative h-[300px] w-full cursor-pointer overflow-hidden md:h-[380px]"
            onMouseMove={handleMouseMove}
            onClick={handleClick}
        >
            <img
                src={src}
                alt={alt}
                className="absolute inset-0 h-full w-full scale-105 object-cover object-center opacity-97 blur-md saturate-[0.92]"
            />
            <img
                ref={hoverOverlayRef}
                src={src}
                alt=""
                aria-hidden="true"
                className="pointer-events-none absolute inset-0 h-full w-full object-cover object-center opacity-0 transition-opacity duration-300 ease-out group-hover:opacity-100"
                style={{
                    maskImage: hoverMask,
                    WebkitMaskImage: hoverMask,
                }}
            />
            <img
                ref={rippleOverlayRef}
                src={src}
                alt=""
                aria-hidden="true"
                className="pointer-events-none absolute inset-0 h-full w-full object-cover object-center opacity-0"
            />
        </div>
    );
}
