import type { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <svg
            {...props}
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            stroke="currentColor"
            strokeWidth="0.7"
            strokeLinecap="round"
            strokeLinejoin="round"
        >
            <rect x="9.9" y="1.4" width="1.4" height="3" rx="0.7" />
            <rect x="12.7" y="1.4" width="1.4" height="3" rx="0.7" />

            <ellipse cx="12" cy="5.6" rx="3.8" ry="1.2" />
            <path d="M8.2 5.6 V9.6 M15.8 5.6 V9.6" />

            <ellipse cx="12" cy="9.6" rx="6.3" ry="1.5" />
            <path d="M5.7 9.6 V14.6 M18.3 9.6 V14.6" />

            <ellipse cx="12" cy="14.6" rx="9.3" ry="1.7" />
            <path d="M2.7 14.6 V20.3 Q12 23.7 21.3 20.3 V14.6" />
        </svg>
    );
}
