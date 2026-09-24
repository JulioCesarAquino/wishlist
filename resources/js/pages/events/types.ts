import type { CSSProperties } from 'react';

export type Product = {
    id: number;
    name: string;
    description: string | null;
    image_url: string | null;
    price: number;
    quantity_available: number;
    is_sold_out: boolean;
};

export type FontFamily = 'default' | 'script' | 'serif' | 'classic';

export type EventData = {
    slug: string;
    type: string;
    title: string;
    event_date: string | null;
    description: string | null;
    story: string | null;
    cover_image_url: string | null;
    gallery_urls: string[];
    primary_color: string | null;
    secondary_color: string | null;
    font_color_primary: string | null;
    font_color_secondary: string | null;
    font_family: FontFamily | null;
    is_published: boolean;
    visits_count: number;
    mp_public_key: string | null;
};

export type Guest = {
    name: string;
    whatsapp: string;
    email: string | null;
    rsvp_status: 'confirmed' | 'declined' | null;
    rsvp_guests_count: number | null;
};

export const EVENT_TYPE_LABELS: Record<string, string> = {
    casamento: 'Casamento',
    cha_bebe: 'Chá de bebê',
    cha_panela: 'Chá de panela',
    aniversario: 'Aniversário',
    outro: 'Evento',
};

export function formatCurrency(value: number): string {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(value);
}

type FontDefinition = {
    label: string;
    headingFamily: string;
    googleFontsHref: string | null;
};

export const FONT_FAMILIES: Record<FontFamily, FontDefinition> = {
    default: {
        label: 'Moderna',
        headingFamily: 'inherit',
        googleFontsHref: null,
    },
    script: {
        label: 'Manuscrita',
        headingFamily: "'Dancing Script', cursive",
        googleFontsHref:
            'https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap',
    },
    serif: {
        label: 'Elegante',
        headingFamily: "'Playfair Display', serif",
        googleFontsHref:
            'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&display=swap',
    },
    classic: {
        label: 'Clássica',
        headingFamily: "'Cormorant Garamond', serif",
        googleFontsHref:
            'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&display=swap',
    },
};

export const DEFAULT_PRIMARY_COLOR = '#f5f1e8';
export const DEFAULT_SECONDARY_COLOR = '#8a9a7e';
export const DEFAULT_FONT_COLOR_PRIMARY = '#3d3d2f';
export const DEFAULT_FONT_COLOR_SECONDARY = '#6b6b5a';

/**
 * A subtle paper-grain texture, generated procedurally (no external image),
 * tinted with the event's primary color.
 */
export function grainBackgroundStyle(
    primaryColor: string | null,
): CSSProperties {
    const noise =
        "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.35'/%3E%3C/svg%3E\")";

    return {
        backgroundColor: primaryColor ?? DEFAULT_PRIMARY_COLOR,
        backgroundImage: noise,
    };
}

export function headingStyle(event: EventData): CSSProperties {
    return {
        fontFamily: FONT_FAMILIES[event.font_family ?? 'default'].headingFamily,
        color: event.font_color_primary ?? DEFAULT_FONT_COLOR_PRIMARY,
    };
}

export function bodyTextStyle(event: EventData): CSSProperties {
    return {
        color: event.font_color_secondary ?? DEFAULT_FONT_COLOR_SECONDARY,
    };
}

export function accentButtonStyle(event: EventData): CSSProperties {
    return {
        backgroundColor: event.secondary_color ?? DEFAULT_SECONDARY_COLOR,
        color: '#ffffff',
    };
}

/**
 * Overrides the fixed, theme-independent `--color-muted-foreground` token
 * (see the `.event-page` CSS class) with the event's own secondary font
 * color, so placeholder text in inputs stays legible against dark themes
 * instead of using a gray tuned only for light backgrounds.
 */
export function mutedTextCssVars(event: EventData): CSSProperties {
    return {
        '--color-muted-foreground':
            event.font_color_secondary ?? DEFAULT_FONT_COLOR_SECONDARY,
    } as CSSProperties;
}
