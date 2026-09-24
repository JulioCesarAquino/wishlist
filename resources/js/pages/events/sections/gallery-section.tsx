import { Images } from 'lucide-react';
import { useState } from 'react';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    bodyTextStyle,
    headingStyle,
    type EventData,
} from '@/pages/events/types';

type Props = {
    event: EventData;
    galleryUrls: string[];
};

export function GallerySection({ event, galleryUrls }: Props) {
    const [previewImage, setPreviewImage] = useState<string | null>(null);

    if (galleryUrls.length === 0) {
        return (
            <div
                className="mx-auto max-w-3xl px-6 py-16 text-center"
                style={bodyTextStyle(event)}
            >
                <Images className="mx-auto mb-3 size-10 opacity-40" />
                Ainda não há fotos na galeria.
            </div>
        );
    }

    return (
        <div className="mx-auto max-w-4xl px-6 py-8">
            <h2
                className="mb-4 text-center text-2xl font-semibold"
                style={headingStyle(event)}
            >
                Galeria
            </h2>
            <div className="grid grid-cols-2 gap-2 sm:grid-cols-3">
                {galleryUrls.map((url) => (
                    <button
                        key={url}
                        type="button"
                        onClick={() => setPreviewImage(url)}
                        className="aspect-square overflow-hidden rounded-lg"
                    >
                        <img
                            src={url}
                            alt=""
                            className="h-full w-full object-cover transition hover:scale-105"
                        />
                    </button>
                ))}
            </div>

            <Dialog
                open={previewImage !== null}
                onOpenChange={(open) => !open && setPreviewImage(null)}
            >
                <DialogContent className="sm:max-w-2xl">
                    <DialogHeader>
                        <DialogTitle className="sr-only">Foto</DialogTitle>
                    </DialogHeader>
                    {previewImage && (
                        <img
                            src={previewImage}
                            alt=""
                            className="w-full rounded-lg"
                        />
                    )}
                </DialogContent>
            </Dialog>
        </div>
    );
}
