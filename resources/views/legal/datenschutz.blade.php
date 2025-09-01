<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Privacy Policy</h2>
                    <p class="text-sm text-slate-400">Information for users</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-8 animate-fade-in-up space-y-6 text-slate-300">
                <p>
                    This privacy notice informs you about the processing of personal data when using this website in
                    accordance with the EU General Data Protection Regulation (GDPR) and the German Federal Data
                    Protection Act (BDSG).
                </p>

                <div>
                    <h3 class="text-white font-semibold mb-2">Controller (Art. 4 No. 7 GDPR)</h3>
                    <p>Mike Rauch, Im Turmswinkel 12, 38122 Braunschweig, Germany</p>
                    <p>Contact: <a class="text-primary-300"
                            href="mailto:privacy@issueforge.com">privacy@issueforge.com</a></p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Data Categories and Purposes</h3>
                    <ul class="list-disc list-inside">
                        <li>Account data (name, email) — to provide authentication and account management</li>
                        <li>Content data (tickets, comments, uploads) — to operate the ticket system</li>
                        <li>Usage and log data (IP address, timestamps, actions) — to ensure security and troubleshoot
                        </li>
                        <li>Communication data (notifications, emails) — to inform you about ticket activity</li>
                    </ul>
                    <p class="mt-2">Legal bases (Art. 6(1) GDPR): performance of contract (b), legitimate interests
                        (f), consent (a) where applicable.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Retention</h3>
                    <p>We store personal data only as long as necessary for the respective purpose or statutory
                        retention obligations. Logs are typically kept for up to 90 days unless needed for security or
                        legal claims.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Recipients</h3>
                    <p>Personal data is processed on servers operated by us. If third‑party processors (e.g., email
                        provider) are used, they are bound by data processing agreements (Art. 28 GDPR).</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Third Country Transfers</h3>
                    <p>No regular transfer to third countries outside the EU/EEA. If unavoidable (e.g., email provider),
                        we rely on an adequacy decision (Art. 45) or Standard Contractual Clauses (Art. 46).</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Cookies & Tracking</h3>
                    <p>Only technically necessary cookies are used for authentication and session management. No
                        marketing tracking is employed.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Your Rights (Art. 15‑22 GDPR)</h3>
                    <ul class="list-disc list-inside">
                        <li>Right of access, rectification, erasure</li>
                        <li>Restriction of processing, data portability</li>
                        <li>Right to object to processing based on Art. 6(1)(f)</li>
                        <li>Right to withdraw consent at any time with effect for the future</li>
                    </ul>
                    <p class="mt-2">You also have the right to lodge a complaint with the competent supervisory
                        authority (Art. 77 GDPR).</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Security</h3>
                    <p>We implement appropriate technical and organizational measures (Art. 32 GDPR) to protect your
                        data (TLS encryption, access controls, backups).</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-2">Contact</h3>
                    <p>For privacy inquiries, exercise of rights, or objections, contact: <a class="text-primary-300"
                            href="mailto:privacy@issueforge.com">privacy@issueforge.com</a>.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
