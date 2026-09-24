import { Separator } from '@/components/ui/separator';
import {
    bodyTextStyle,
    googleMapsEmbedUrl,
    headingStyle,
    type EventData,
} from '@/pages/events/types';

export function LocationSection({ event }: { event: EventData }) {
    if (!event.address) {
        return null;
    }

    const mapUrl = googleMapsEmbedUrl(event);

    return (
        <>
            <Separator className="my-8" />
            <h2
                className="mb-4 text-2xl font-semibold"
                style={headingStyle(event)}
            >
                Localização
            </h2>
            <p
                className="mb-4 leading-relaxed whitespace-pre-line"
                style={bodyTextStyle(event)}
            >
                {event.address}
            </p>
            {mapUrl && (
                <div className="aspect-video overflow-hidden rounded-lg">
                    <iframe
                        title="Localização do evento"
                        src={mapUrl}
                        className="h-full w-full border-0"
                        loading="lazy"
                        referrerPolicy="strict-origin-when-cross-origin"
                        allowFullScreen
                    />
                </div>
            )}
        </>
    );
}
