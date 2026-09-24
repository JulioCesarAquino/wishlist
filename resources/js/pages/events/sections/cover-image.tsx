import { useRef } from 'react';

type Props = {
    src: string;
    alt: string;
};

/**
 * The cover starts softly blurred/dimmed (an elegant "out of focus" look).
 * Moving the mouse reveals the sharp photo inside a circle that follows the
 * cursor; moving away lets it fade back to the blurred version.
 */
export function CoverImage({ src, alt }: Props) {
    const overlayRef = useRef<HTMLImageElement>(null);

    const handleMouseMove = (event: React.MouseEvent<HTMLDivElement>) => {
        const rect = event.currentTarget.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width) * 100;
        const y = ((event.clientY - rect.top) / rect.height) * 100;

        overlayRef.current?.style.setProperty('--reveal-x', `${x}%`);
        overlayRef.current?.style.setProperty('--reveal-y', `${y}%`);
    };

    const revealMask = [
        'radial-gradient(circle 230px at calc(var(--reveal-x, 50%) - 25px) calc(var(--reveal-y, 50%) + 20px), black 45%, transparent 100%)',
        'radial-gradient(circle 190px at calc(var(--reveal-x, 50%) + 30px) calc(var(--reveal-y, 50%) - 15px), black 45%, transparent 100%)',
        'radial-gradient(circle 200px at var(--reveal-x, 50%) var(--reveal-y, 50%), black 45%, transparent 100%)',
    ].join(', ');

    return (
        <div
            className="group relative h-[300px] w-full overflow-hidden md:h-[380px]"
            onMouseMove={handleMouseMove}
        >
            <img
                src={src}
                alt={alt}
                className="absolute inset-0 h-full w-full scale-105 object-cover object-center opacity-97 blur-md saturate-[0.92]"
            />
            <img
                ref={overlayRef}
                src={src}
                alt=""
                aria-hidden="true"
                className="absolute inset-0 h-full w-full object-cover object-center opacity-0 transition-opacity duration-300 ease-out group-hover:opacity-100"
                style={{
                    maskImage: revealMask,
                    WebkitMaskImage: revealMask,
                }}
            />
        </div>
    );
}
