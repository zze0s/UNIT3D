<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('user.irckeys') }}</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="irckey"
                        class="form__text"
                        type="search"
                        autocomplete="off"
                        wire:model.live="irckey"
                        placeholder=" "
                    />
                    <label class="form__label form__label--floating" for="irckey">
                        {{ __('user.irckey') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="username"
                        class="form__text"
                        type="search"
                        autocomplete="off"
                        wire:model.live="username"
                        placeholder=" "
                    />
                    <label class="form__label form__label--floating" for="username">
                        {{ __('common.username') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <select id="quantity" class="form__select" wire:model.live="perPage" required>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <label class="form__label form__label--floating" for="quantity">
                        {{ __('common.quantity') }}
                    </label>
                </div>
            </div>
        </div>
    </header>
    <div class="data-table-wrapper">
        <table class="data-table">
            <tbody>
                <tr>
                    <th wire:click="sortBy('user_id')" role="columnheader button">
                        {{ __('common.username') }}
                        @include('livewire.includes._sort-icon', ['field' => 'user_id'])
                    </th>
                    <th wire:click="sortBy('content')" role="columnheader button">
                        {{ __('user.irckey') }}
                        @include('livewire.includes._sort-icon', ['field' => 'content'])
                    </th>
                    <th wire:click="sortBy('created_at')" role="columnheader button">
                        {{ __('common.created_at') }}
                        @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                    </th>
                    <th wire:click="sortBy('deleted_at')" role="columnheader button">
                        {{ __('user.deleted-on') }}
                        @include('livewire.includes._sort-icon', ['field' => 'deleted_at'])
                    </th>
                </tr>
                @forelse ($irckeys as $irckey)
                    <tr>
                        <td>
                            <x-user-tag :user="$irckey->user" :anon="false" />
                        </td>
                        <td>{{ $irckey->content }}</td>
                        <td>
                            <time
                                datetime="{{ $irckey->created_at }}"
                                title="{{ $irckey->created_at }}"
                            >
                                {{ $irckey->created_at }}
                            </time>
                        </td>
                        <td>
                            <time
                                datetime="{{ $irckey->deleted_at }}"
                                title="{{ $irckey->deleted_at }}"
                            >
                                {{ $irckey->deleted_at ?? 'Currently in use' }}
                            </time>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No irckeys</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $irckeys->links('partials.pagination') }}
</section>
