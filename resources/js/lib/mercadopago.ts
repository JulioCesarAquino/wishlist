export type MercadoPagoBrickController = {
    unmount: () => void;
};

export type MercadoPagoInstance = {
    bricks: () => {
        create: (
            type: string,
            containerId: string,
            settings: Record<string, unknown>,
        ) => Promise<MercadoPagoBrickController>;
    };
};

declare global {
    interface Window {
        MercadoPago?: new (
            publicKey: string,
            options?: { locale?: string },
        ) => MercadoPagoInstance;
    }
}

const SDK_URL = 'https://sdk.mercadopago.com/js/v2';

let scriptPromise: Promise<void> | null = null;

function loadScript(): Promise<void> {
    if (window.MercadoPago) {
        return Promise.resolve();
    }

    if (!scriptPromise) {
        scriptPromise = new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = SDK_URL;
            script.onload = () => resolve();
            script.onerror = () =>
                reject(new Error('Falha ao carregar o SDK do Mercado Pago'));
            document.head.appendChild(script);
        });
    }

    return scriptPromise;
}

export async function loadMercadoPago(
    publicKey: string,
): Promise<MercadoPagoInstance> {
    await loadScript();

    if (!window.MercadoPago) {
        throw new Error('SDK do Mercado Pago indisponível');
    }

    return new window.MercadoPago(publicKey, { locale: 'pt-BR' });
}
