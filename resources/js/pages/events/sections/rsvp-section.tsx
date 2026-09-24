import { useForm } from '@inertiajs/react';
import { CalendarCheck, CalendarX } from 'lucide-react';
import { useState } from 'react';
import { store as storeRsvp } from '@/routes/rsvp';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    accentButtonStyle,
    bodyTextStyle,
    headingStyle,
    type EventData,
    type Guest,
} from '@/pages/events/types';

type Props = {
    event: EventData;
    guest: Guest | null;
};

export function RsvpSection({ event, guest }: Props) {
    const [justResponded, setJustResponded] = useState(false);
    const [editing, setEditing] = useState(false);

    const form = useForm({
        guest: {
            name: guest?.name ?? '',
            whatsapp: guest?.whatsapp ?? '',
            email: guest?.email ?? '',
        },
        attending: null as boolean | null,
        guests_count: guest?.rsvp_guests_count ?? 1,
    });

    const submit = () => {
        form.post(storeRsvp({ event: event.slug }).url, {
            preserveScroll: true,
            onSuccess: () => {
                setJustResponded(true);
                setEditing(false);
            },
        });
    };

    const alreadyResponded = guest?.rsvp_status && !editing;

    if (alreadyResponded || justResponded) {
        const attending = justResponded
            ? form.data.attending
            : guest?.rsvp_status === 'confirmed';
        const count = justResponded
            ? form.data.guests_count
            : guest?.rsvp_guests_count;

        return (
            <div className="mx-auto max-w-lg px-6 py-16 text-center">
                {attending ? (
                    <CalendarCheck
                        className="mx-auto mb-3 size-10"
                        style={{ color: event.secondary_color ?? undefined }}
                    />
                ) : (
                    <CalendarX
                        className="mx-auto mb-3 size-10 opacity-50"
                        style={bodyTextStyle(event)}
                    />
                )}
                <h2
                    className="mb-2 text-2xl font-semibold"
                    style={headingStyle(event)}
                >
                    {attending
                        ? `Presença confirmada para ${count} pessoa${count === 1 ? '' : 's'}!`
                        : 'Você avisou que não vai poder comparecer.'}
                </h2>
                <p className="mb-6" style={bodyTextStyle(event)}>
                    Obrigado por responder.
                </p>
                <Button variant="outline" onClick={() => setEditing(true)}>
                    Alterar resposta
                </Button>
            </div>
        );
    }

    return (
        <div className="mx-auto max-w-lg px-6 py-8">
            <h2
                className="mb-1 text-center text-2xl font-semibold"
                style={headingStyle(event)}
            >
                Confirmar presença
            </h2>
            <p className="mb-6 text-center" style={bodyTextStyle(event)}>
                Avise se você vai poder comparecer ao evento.
            </p>

            <div className="space-y-4">
                <div className="grid gap-1.5">
                    <Label htmlFor="rsvp_name">Seu nome</Label>
                    <Input
                        id="rsvp_name"
                        value={form.data.guest.name}
                        onChange={(e) =>
                            form.setData('guest', {
                                ...form.data.guest,
                                name: e.target.value,
                            })
                        }
                    />
                    {form.errors['guest.name'] && (
                        <p className="text-sm text-red-600">
                            {form.errors['guest.name']}
                        </p>
                    )}
                </div>

                <div className="grid gap-1.5">
                    <Label htmlFor="rsvp_whatsapp">WhatsApp</Label>
                    <Input
                        id="rsvp_whatsapp"
                        value={form.data.guest.whatsapp}
                        onChange={(e) =>
                            form.setData('guest', {
                                ...form.data.guest,
                                whatsapp: e.target.value,
                            })
                        }
                    />
                    {form.errors['guest.whatsapp'] && (
                        <p className="text-sm text-red-600">
                            {form.errors['guest.whatsapp']}
                        </p>
                    )}
                </div>

                <div className="grid gap-1.5">
                    <Label htmlFor="rsvp_email">E-mail (opcional)</Label>
                    <Input
                        id="rsvp_email"
                        type="email"
                        value={form.data.guest.email}
                        onChange={(e) =>
                            form.setData('guest', {
                                ...form.data.guest,
                                email: e.target.value,
                            })
                        }
                    />
                </div>

                <div className="grid gap-1.5">
                    <Label>Você vai comparecer?</Label>
                    <div className="flex gap-3">
                        <Button
                            type="button"
                            variant={
                                form.data.attending === true
                                    ? 'default'
                                    : 'outline'
                            }
                            className="flex-1"
                            style={
                                form.data.attending === true
                                    ? accentButtonStyle(event)
                                    : undefined
                            }
                            onClick={() => form.setData('attending', true)}
                        >
                            Vou comparecer
                        </Button>
                        <Button
                            type="button"
                            variant={
                                form.data.attending === false
                                    ? 'default'
                                    : 'outline'
                            }
                            className="flex-1"
                            onClick={() => form.setData('attending', false)}
                        >
                            Não vou poder
                        </Button>
                    </div>
                    {form.errors.attending && (
                        <p className="text-sm text-red-600">
                            {form.errors.attending}
                        </p>
                    )}
                </div>

                {form.data.attending === true && (
                    <div className="grid gap-1.5">
                        <Label htmlFor="rsvp_count">
                            Quantas pessoas (incluindo você)?
                        </Label>
                        <Input
                            id="rsvp_count"
                            type="number"
                            min={1}
                            max={20}
                            value={form.data.guests_count}
                            onChange={(e) =>
                                form.setData(
                                    'guests_count',
                                    Number(e.target.value),
                                )
                            }
                        />
                        {form.errors.guests_count && (
                            <p className="text-sm text-red-600">
                                {form.errors.guests_count}
                            </p>
                        )}
                    </div>
                )}

                <Button
                    className="w-full"
                    style={accentButtonStyle(event)}
                    disabled={form.processing || form.data.attending === null}
                    onClick={submit}
                >
                    Enviar confirmação
                </Button>
            </div>
        </div>
    );
}
