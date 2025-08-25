
<x-app-layout>
    <x-slot name="header" >
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Case Info') }}
        </h2>
    </x-slot>

    <div class="text-gray-900 px-3 py-3 grid grid-cols-1 2xl:grid-cols-12 gap-2">
        <div class="2xl:col-span-8 flex flex-col h-full">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-3 text-gray-900">

                    <form method="post" action="{{ route('profile.update') }}" class="mt-1">
                        @csrf
                        <div class="flex float-right">
                            <x-primary-btn class="">{{ __('Save') }}</x-primary-btn>

                            @if (session('status') === 'profile-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600"
                                >{{ __('Saved.') }}</p>
                            @endif
                        </div>
                        @include('case-info.contact-info')
                        @include('case-info.case-info')
                    </form>

                </div>
            </div>
        </div>
        <div class="2xl:col-span-2 flex flex-col h-full ">
            <div class=" flex flex-col h-full">
                <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-2">
                        {{-- @include('case-info.headerss') --}}
                           <p>
                            <strong>To Do:</strong><br>
                            8/25/2025<br>
                            AAA<br>
                            - Fill out info “Case Info Part 1”<br>
                            - Sign up client<br>
                            <br>
                            Do the following AFTER you contract back:<br>
                            - Electronic File Created (including subfolders)<br>
                            - Contract saved in efile &/or put in physical file<br>
                            - Hyperlink the Client Folder<br>
                            - NOR 3P (Pre Lit Forms)<br>
                            - NOR 1P (if 1P claim already set up)…if 1P claim not set up, delete this to do<br>
                            - Look up crash report (if there is one) CRIS | Lexis<br>
                            <br>
                            Re-calendar 1 business day out:<br>
                            AAA<br>
                            - CLC<br>
                            <br>
                            Do the following AFTER you CLC:<br>
                            - Physical File created<br>
                            - Public Contacts<br>
                            - New Client Letter “PL – 0.13” English | Spanish<br>
                            - Calendar the SOL as “*SOL* - CLIENT NAME”<br>
                            - Calendar 30 Days Before SOL as “30 DAYS to *SOL* - CLIENT NAME”<br>
                            - Four Pillars or Saaber request for limits (if not Infinity/Kemper, American Access, Loya, etc.)<br>
                            - IF client went to hospital, order MR/BR/affs from hospital.<br>
                            <br>
                            <strong>Attorney/Supervisor To Do List</strong><br>
                            - Deposit request email sent & info inserted in Deposits/Expenses/Advances chart<br>
                            - Referral source updated that deposit went out per the CLC Updates List<br>
                            <br>
                            <strong>TREATMENT PLAN:</strong> 1 ESI/2 ESI/3 ESIs + Surgery (if 3P min, ask client for dec page)<br>
                            <br>
                            <strong>What’s been done:</strong><br>
                            00/00/00<br>
                            Spoke to potential client about their caseeee<br>
                            </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="2xl:col-span-2 flex flex-col h-full ">
            <div class=" flex flex-col h-full">
                <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-2">
                        {{-- @include('case-info.headersss') --}}
                            <p><strong>To Do Checklists</strong></p>
                            <p>Drag & drop the following to dos that are in red into the “To Do:” section of the middle column as the case unfolds.<br>
                            (Add/change to do based on the situation & common sense)</p>

                            <h3>Treating</h3>

                            <p>2 weeks post-sign up:</p>
                            <ul>
                            <li>FU on treatment: How is therapy going?</li>
                            </ul>

                            <p>4-6 weeks post-accident:</p>
                            <ul>
                            <li>FU on treatment: Have you been scheduled for your MRIs yet?</li>
                            </ul>

                            <p>Once MRIs are scheduled, re-calendar 7 business days post-MRIs:</p>
                            <ul>
                            <li>FU on treatment: How did your MRIs go? Have you reviewed the results with your doctor? (If Yes, then…)</li>
                            <li>The next step is pain management. Soon you will be scheduled for a pain management consultation.</li>
                            <li>Update treating chart</li>
                            </ul>

                            <p>Once PM consult is scheduled, re-calendar 3 days post-PM consult:</p>
                            <ul>
                            <li>FU on treatment: Have you been scheduled for your first injection yet? <em>INJECTION TALK</em></li>
                            </ul>

                            <p>Use this scenario if only 1 injection approved:</p>
                            <ul>
                            <li>Once injection #1 is scheduled, re-calendar 3 days post-injection #1:</li>
                            <ul>
                                <li>FU with PM facility or client to confirm client got injection.</li>
                                <li>Once confirmed, talk to client about next steps: Discharge from chiro.</li>
                                <li>Update treating chart – 1st injection 00/00/00</li>
                                <li>Put the case into Discharged once client has had all approved injections, and move into Discharged to dos.</li>
                            </ul>
                            </ul>

                            <p>Use this scenario if 2+ injections approved:</p>
                            <ul>
                            <li>Once injection #1 is scheduled, re-calendar 3 days post-injection #1:</li>
                            <ul>
                                <li>FU with PM facility OR client to confirm client got injection.</li>
                                <li>Once confirmed, update treating chart – 1st injection 00/00/00</li>
                            </ul>
                            </ul>

                            <p>Once FU consult is scheduled, re-calendar 3 days post FU consult:</p>
                            <ul>
                            <li>FU on treatment: When is your 2nd injection scheduled?</li>
                            </ul>

                            <p>Once injection #2 is scheduled, re-calendar 7 days post-injection #2:</p>
                            <ul>
                            <li>FU with PM facility or client to confirm client got injection, and ask if FU consult has been scheduled.</li>
                            <li>Update treating chart – 2nd injection 00/00/00</li>
                            </ul>

                            <p>Once FU consult is scheduled, re-calendar 3 days post FU consult:</p>
                            <ul>
                            <li>FU on treatment: Have you been scheduled for your 3rd injection yet?</li>
                            </ul>

                            <p>Once injection #3 is scheduled, re-calendar 7 days post-injection #3:</p>
                            <ul>
                            <li>FU with PM facility or client to confirm client got injection.</li>
                            <li>Once confirmed, talk to client about next steps: Discharge from chiro.</li>
                            <li>Update treating chart – 2nd injection 00/00/00</li>
                            </ul>

                            <p>Repeat relevant to dos if client approved for 4, 5, & 6 injections.</p>
                            <ul>
                            <li>Put the case into Discharged once client has had all approved injections (if no injections, then once client has had MRIs, and is wrapping up with chiro).</li>
                            </ul>

                            <h3>Discharged</h3>
                            <ul>
                            <li>Send RECORDS request w/ Affs we need to records@nmwlawfirm.com EXCEPT chiro (unless they are an exception – GW your supervisor)</li>
                            <li>Do not request records/bills we already have</li>
                            </ul>

                            <p>Re-Calendar 4 weeks later:</p>
                            <ul>
                            <li>Check Dropbox</li>
                            <li>FU with RECORDS via email on anything we still don’t have</li>
                            <li>If we have 99% of relevant records, and you’re just missing one minor report, still send demand</li>
                            <li>If we don’t have chiro records by now, send email to NMW with name of client and chiropractor and tell him we still don’t have chiro records</li>
                            </ul>

                            <p>Once we get every last set of records:</p>
                            <ul>
                            <li>DO DEMAND (Pre Lit Forms)</li>
                            </ul>

                            <p>After you send the demand:</p>
                            <ul>
                            <li>Copy and paste the A-F into the Neg chart</li>
                            <li>Update 18.001 Affidavit Chart</li>
                            <li>Update the total meds and last demand boxes in the Neg Chart</li>
                            <li>Put CMET into Negotiating…then…</li>
                            </ul>

                            <h3>Negotiating</h3>
                            <p>Calendar 1 business day after sending demand:</p>
                            <ul>
                            <li>Confirm receipt of demand</li>
                            <li>Re-calendar every day until you confirm receipt of demand</li>
                            </ul>

                            <p>Once you have confirmed receipt of demand, re-calendar 2 weeks out:</p>
                            <ul>
                            <li>FU on demand eval</li>
                            </ul>

                            <p>Once you get first offer:</p>
                            <ul>
                            <li>Ask adjuster to send in writing (if offer over the phone)</li>
                            <li>Save offer to efile</li>
                            <li>Insert offer into Neg chart</li>
                            <li>Calendar to do CS next day</li>
                            <li>Copy and paste CS Paragraphs & recalendar to supervisor for CS, BL, BBL updates</li>
                            </ul>

                            <p><em>CS Paragraphs</em></p>
                            <ul>
                            <li>CS1 - $00,000.00</li>
                            <li>CS2 - $00,000.00</li>
                            <li>CS3 - $00,000.00</li>
                            <li>CS4 - $00,000.00</li>
                            <li>BL - $00,000.00</li>
                            <li>BBL - $00,000.00</li>
                            </ul>

                            <p>Send CSs after 24 hrs of getting an offer. FU on CSs every 3 business days.</p>

                            <p>IF there is 1P, send demands to both 3P and 1P at the same time:</p>
                            <ul>
                            <li>Once get PL from 3P, request dec page and release (from 3P)</li>
                            <li>Send 3P dec page and release to 1P attached to a “permission to settle” letter</li>
                            <li>Calendar 2 business days later: FU on demand eval with 1P</li>
                            </ul>

                            <p><strong>Acronym Key:</strong></p>
                            <ul>
                            <li>CR – Confirm receipt</li>
                            <li>CS – Counter settlement offer</li>
                            <li>BL – Bottom line</li>
                            <li>BBL – Bottom bottom line (lowest we will take)</li>
                            </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
