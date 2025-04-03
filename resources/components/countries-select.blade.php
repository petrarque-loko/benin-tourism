@php
    $countries = app('rinvex.countries');
    $sortedCountries = $countries->all()->sortBy('name.common');
@endphp

<select id="{{ $id }}" class="{{ $class ?? 'form-select' }} @error($name) is-invalid @enderror" name="{{ $name }}" {{ $required ? 'required' : '' }}>
    <option value="">Sélectionnez un pays</option>
    @foreach($sortedCountries as $countryCode => $country)
        @php
            $phoneCode = isset($country['dialling']['calling_code']) ? '+' . $country['dialling']['calling_code'][0] : '';
            $countryName = isset($country['name']['native']['fra']['common']) ? $country['name']['native']['fra']['common'] : $country['name']['common'];
            $flag = isset($country['emoji']) ? $country['emoji'] : '';
            $value = $phoneCode . '|' . $countryName;
        @endphp
        <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
            {{ $flag }} {{ $countryName }} ({{ $phoneCode }})
        </option>
    @endforeach
</select>