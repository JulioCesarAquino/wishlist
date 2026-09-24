import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { CoverImage } from '@/pages/events/sections/cover-image';
import { Countdown } from '@/pages/events/sections/countdown';
import {
    bodyTextStyle,
    EVENT_TYPE_LABELS,
    headingStyle,
    type EventData,
} from '@/pages/events/types';

export function HomeSection({ event }: { event: EventData }) {
    return (
        <div>
            {event.cover_image_url && (
                <CoverImage src={event.cover_image_url} alt={event.title} />
            )}

            <div className="mx-auto max-w-3xl px-6 py-10 text-center">
                <h1
                    className="mb-2 text-5xl font-semibold"
                    style={headingStyle(event)}
                >
                    {event.title}
                </h1>
                <Badge variant="secondary" className="mb-3">
                    {EVENT_TYPE_LABELS[event.type] ?? event.type}
                </Badge>
                {event.event_date && (
                    <p className="mb-2 text-sm" style={bodyTextStyle(event)}>
                        {new Date(
                            `${event.event_date}T00:00:00`,
                        ).toLocaleDateString('pt-BR', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric',
                        })}
                    </p>
                )}

                <Countdown event={event} />

                {event.description && (
                    <p
                        className="mt-4 leading-relaxed whitespace-pre-line"
                        style={bodyTextStyle(event)}
                    >
                        {event.description}
                    </p>
                )}

                {event.story && (
                    <>
                        <Separator className="my-8" />
                        <h2
                            className="mb-4 text-2xl font-semibold"
                            style={headingStyle(event)}
                        >
                            Nossa história
                        </h2>
                        <p
                            className="leading-relaxed whitespace-pre-line"
                            style={bodyTextStyle(event)}
                        >
                            {event.story}
                        </p>
                    </>
                )}
            </div>
        </div>
    );
}
