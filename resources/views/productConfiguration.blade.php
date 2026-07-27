<x-app-layout>
    <x-slot name="header" >
        <div class="py-4 px-4 bg-white shadow sm:rounded-lg">
            <h2 class=" text-xl text-gray-500 leading-tight">
                <i class="fa-solid fa-ship mr-2 text-[#B6844A]"></i>{{ __('Product Configuration') }}
            </h2>
        </div>
    </x-slot>
    <div class=" mx-auto py-2 px-4">
        <div class="p-3 bg-white shadow sm:rounded-lg">
            <x-grid   class="2xl:grid-cols-12">

                <x-col class="2xl:col-span-3 px-3">
                    <h2 class=" text-lg  text-gray-700 text-center p-2 border-t-1 border-b-1 border-gray-200 ">
                        <i
                            x-data
                            @click="$dispatch('open-modal', 'add-product')"
                            id="addAdministrationProduct" class="fa-solid fa-circle-plus cursor-pointer text-[#B6844A] mr-2"
                            class="fa-solid fa-circle-plus cursor-pointer text-[#B6844A] mr-2 hover:text-[#9a6d3c] transition-colors"
                            title="Add Product"
                        ></i>
                        <input type="hidden" id="productIdForDestination" value="">
                        <input type="hidden" id="destinationForResort" value="">
                        <input type="hidden" id="resortForDestination" value="">
                        {{ __('Products') }}
                    </h2>
                    <div class="">
                        @foreach($products as $product)
                            <div  class="flex gap-2.5 p-2 border-b-1 border-gray-200 cursor-pointer " productId="{{ $product->id }}">
                                    <i class="productEdit fas fa-edit text-[#989898] text-[16px]"></i>
                                    <i class="fas fa-trash text-[#989898] text-[16px]"></i>
                                <span class="productItemClick w-100"  productId="{{ $product->id }}">
                                    <p class="text-[#464646]  " >{{ $product->product_name }}</p>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </x-col>

                <x-col class="2xl:col-span-3 px-3">
                    <h2 class=" text-lg  text-gray-700 text-center p-2  border-t-1 border-b-1 border-gray-200">
                        <i
                            id="addAdministrationDestination" class="fa-solid fa-circle-plus cursor-pointer text-[#B6844A] mr-2"
                            class="fa-solid fa-circle-plus cursor-pointer text-[#B6844A] mr-2 hover:text-[#9a6d3c] transition-colors"
                            title="Add Destination"
                            ></i>
                            {{ __('Destinations') }}
                    </h2>
                    <div id="destination">
                    </div>
                </x-col>

                <x-col class="2xl:col-span-3 px-3">
                    <h2 class=" text-lg  text-gray-700 text-center p-2  border-t-1 border-b-1 border-gray-200">
                        <i
                            id="addAdministrationResortShip" class="fa-solid fa-circle-plus cursor-pointer text-[#B6844A] mr-2"
                            class="fa-solid fa-circle-plus cursor-pointer text-[#B6844A] mr-2 hover:text-[#9a6d3c] transition-colors"
                            title="Add Resort Ship"
                        ></i>
                        {{ __('Resort/Ships') }}
                    </h2>
                    <div id="resortShipingContainer">
                    </div>
                </x-col>

                <x-col class="2xl:col-span-3  px-3">
                    <h2 class=" text-lg  text-gray-700 text-center p-2  border-t-1 border-b-1 border-gray-200">
                        <i
                            id="addAdministrationCruise" class="fa-solid fa-circle-plus cursor-pointer text-[#B6844A] mr-2"
                            class="fa-solid fa-circle-plus cursor-pointer text-[#B6844A] mr-2 hover:text-[#9a6d3c] transition-colors"
                            title="Add Cruise"
                        ></i>
                        {{ __('Cruise itineraries') }}
                    </h2>
                    <div id="cruiseItinerariesContainer">
                    </div>
                </x-col>

            </x-grid>
        </div>
    </div>
</x-app-layout>



<x-general-modal name="add-product" focusable>
     <x-slot name="header">
        <div class="grid grid-cols-[auto_1fr] items-center gap-2">
            <i class="fa-solid fa-ship fa-xl text-[#B6844A]" ></i>
            <h2 class="text-xl font-semibold text-gray-600">
                {{ __('Manage Product') }}
            </h2>
        </div>
        <i
            class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
            role="button"
             @click="$dispatch('close-modal', 'add-product')"
            aria-label="Close">
        </i>
    </x-slot>

    <form id="productForm"  class="">
        @csrf
        <input type="hidden" name="_method" id="formMethod" value="POST">
            <x-grid   class="2xl:grid-cols-12">
                <x-col class="2xl:col-span-4 px-3">
                    <x-input-label for="product_name" value="{{ __('Product Name') }}" class="sr-only" />

                    <x-text-input
                        id="product_name"
                        name="product_name"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Product Name') }}"
                    />
                    <x-text-input
                        id="productId"
                        name="productId"
                        type="hidden"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Product Name') }}"
                    />
                </x-col>
                <x-col class="2xl:col-span-4 px-3">
                    <x-input-label for="display_order" value="{{ __('Display Order') }}" class="sr-only" />
                    <x-text-input
                        id="display_order"
                        name="display_order"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Display Order') }}"
                    />
                </x-col>
                <x-col class="2xl:col-span-4 px-3">
                    <x-input-label for="product_type " value="{{ __('product_type ') }}" class="sr-only" />
                    <x-text-input
                        id="product_type"
                        name="product_type"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Product Type ') }}"
                    />
                </x-col>
            </x-grid>

            <x-grid   class="2xl:grid-cols-12">
                <x-col class="2xl:col-span-6 px-3">

                    <x-input-label for="currency" value="{{ __('currency') }}" class="sr-only" />
                     <select style="margin-top:31px; padding-top:0px" class="w-full border-b-2 border-[#bdbdbd] text-[#777] mb-4 focus:outline-none focus:border-[#B6844A]" name="currency" id="currency">
                        <option value="-1">-- Select Currency --</option>
                        <option value="1">CAD</option>
                        <option value="2">USD</option>
                    </select>

                </x-col>

                <x-col class="2xl:col-span-6 px-3">
                    <x-input-label for="tax" value="{{ __('tax') }}" class="sr-only" />
                    <x-text-input
                        id="tax"
                        name="tax"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Tax') }}"
                    />
                </x-col>

            </x-grid>



            <x-grid   class="2xl:grid-cols-12">
                <x-col class="2xl:col-span-4 px-3">
                    <x-input-label for="vendorBDM" value="{{ __('vendorBDM') }}" class="sr-only" />
                    <x-text-input
                        id="vendorBDM"
                        name="vendorBDM"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('BDM') }}"
                    />
                </x-col>

                <x-col class="2xl:col-span-4 px-3">
                    <x-input-label for="bdm_phone_number" value="{{ __('bdm_phone_number') }}" class="sr-only" />
                    <x-text-input
                        id="bdm_phone_number"
                        name="bdm_phone_number"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Bdm Phone Number') }}"
                    />
                </x-col>
                <x-col class="2xl:col-span-4 px-3">
                    <x-input-label for="bdm_email" value="{{ __('bdm_email') }}" class="sr-only" />
                    <x-text-input
                        id="bdm_email"
                        name="bdm_email"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Bdm Email') }}"
                    />
                </x-col>
            </x-grid>


             <x-grid   class="2xl:grid-cols-12">
                <x-col class="2xl:col-span-4 px-3">
                    <x-input-label for="phone_number" value="{{ __('phone_number') }}" class="sr-only" />
                    <x-text-input
                        id="phone_number"
                        name="phone_number"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Phone Number') }}"
                    />
                </x-col>

                <x-col class="2xl:col-span-4 px-3">
                    <x-input-label for="first_address_line" value="{{ __('first_address_line') }}" class="sr-only" />
                    <x-text-input
                        id="first_address_line"
                        name="first_address_line"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('First Address Line') }}"
                    />
                </x-col>
                <x-col class="2xl:col-span-4 px-3">
                    <x-input-label for="second_address_line" value="{{ __('second_address_line') }}" class="sr-only" />
                    <x-text-input
                        id="second_address_line"
                        name="second_address_line"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Second Address Line') }}"
                    />
                </x-col>
            </x-grid>


             <x-grid   class="2xl:grid-cols-12">
                <x-col class="2xl:col-span-3 px-3">
                    <x-input-label for="city" value="{{ __('city') }}" class="sr-only" />
                    <x-text-input
                        id="city"
                        name="city"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('City') }}"
                    />
                </x-col>

                <x-col class="2xl:col-span-3 px-3">
                    <x-input-label for="state" value="{{ __('state') }}" class="sr-only" />
                    <x-text-input
                        id="state"
                        name="state"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('State') }}"
                    />
                </x-col>

                <x-col class="2xl:col-span-3 px-3">
                    <x-input-label for="postal_code" value="{{ __('postal_code') }}" class="sr-only" />
                    <x-text-input
                        id="postal_code"
                        name="postal_code"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Postal Code') }}"
                    />
                </x-col>

                <x-col class="2xl:col-span-3 px-3">
                    <x-input-label for="country" value="{{ __('country') }}" class="sr-only" />
                    <x-text-input
                        id="country"
                        name="country"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Country') }}"
                    />
                </x-col>
            </x-grid>


            <x-grid   class="2xl:grid-cols-12">
                <x-col class="2xl:col-span-12 px-3">
                    <x-input-label for="notes" value="{{ __('notes') }}" class="sr-only" />
                    <x-text-input
                        id="notes"
                        name="notes"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Notes') }}"
                    />
                </x-col>
            </x-grid>

            {{-- <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" /> --}}

    </form>

     <x-slot name="footer">
        <div id="productFormErrors" class=" text-red-500 ml-1">

        </div>
        <div class="flex justify-end ">
            <x-secondary-btn x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-btn>

            <x-primary-btn id="saveProductBtn" class="ms-3">
                {{ __('Save') }}
            </x-primary-btn>
        </div>
    </x-slot>
</x-general-modal>



<x-general-modal name="add-others" focusable>
     <x-slot name="header">
        <div class="grid grid-cols-[auto_1fr] items-center gap-2">
            <i class="fa-solid fa-ship fa-xl text-[#B6844A]" ></i>
            <h2 id="otherModalTitle" class="text-xl font-semibold text-gray-800">
                {{ __('Manage Destination') }}
            </h2>
        </div>
        <i
            class="fa-solid fa-xmark fa-xl text-red-500 hover:text-red-600 transition-colors duration-200 cursor-pointer justify-self-end custom-close-icon"
            role="button"
             @click="$dispatch('close-modal', 'add-others')"
            aria-label="Close">
        </i>
    </x-slot>

    <form id="othersForm"  class="p-6">
        @csrf
        <input type="hidden" name="_method" id="othersFormMethod" value="POST">

        <div class="mt-6">
            <x-input-label for="nameOtherModal" value="{{ __('nameOtherModal') }}" class="sr-only" />
            <x-text-input
                id="nameOtherModal"
                name="nameOtherModal"
                type="text"
                class="mt-1 block w-3/4"
                placeholder="{{ __('Name') }}"
            />

            {{-- <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" /> --}}
        </div>

    </form>

     <x-slot name="footer">
        <div class="flex justify-end ">
            <x-secondary-btn x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-btn>

            <x-primary-btn id="saveOthersBtn" class="ms-3">
                {{ __('Save') }}
            </x-primary-btn>
        </div>
    </x-slot>
</x-general-modal>


