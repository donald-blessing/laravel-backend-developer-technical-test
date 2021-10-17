<div>
    <div class="mt-4">
        <label for="type">User</label>
        <select id="type" class="block w-full mt-1" name="type" required wire:model="type">
            <option>Choose User</option>
            <option value="customer">Customer</option>
            <option value="service">Service Renderer</option>
        </select>
    </div>

    @if ($type=='service' )
    @if ($services->count()>0)
    <div class="mt-4">
        <label for="service">Select the service you render</label>
        <select id="service" class="block w-full mt-1" name="service" wire:model='service' required>
            <option>Choose service</option>
            @foreach ($services as $service)
            <option value="{{ $service->slug }}">{{ ucwords($service->name) }}</option>
            @endforeach
            <option value="other">Other...</option>
        </select>
    </div>
    @else
    <div class="mt-4">
        <label for="service">Service you render</label>
        <input type="text" id="service" class="block w-full mt-1" name="service" value="" placeholder="Enter the name of the service you render" required>
    </div>
    @endif

    @if ($otherSelected)
    <div class="mt-4">
        <label for="service_type">Service you render</label>
        <input type="text" id="service_type" class="block w-full mt-1" name="service_type" value="" placeholder="Enter the name of the service you render" required>
    </div>
    @endif
    @endif
</div>
