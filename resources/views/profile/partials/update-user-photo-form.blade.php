<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update your photo') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Choose a photo to update.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    @if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif



    <form method="post" action="{{ route('profile.photo') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')
      
        <img src="{{"/storage/$user->photo"}}" alt="user photo"/>

        <div>
            <x-input-label for="photo" :value="__('Photo')" />
            <x-text-input id="photo" name="photo" type="file" class="mt-1 block w-full" :value="old('photo', $user->photo)"  autofocus autocomplete="photo" />
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

   

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

        </div>
    </form>






</section>
