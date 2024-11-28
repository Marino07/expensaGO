<div>
    <h2 class="text-2xl font-bold">Your Trip Plan</h2>
    @if($errorMessage)
        <p class="text-red-500">{{ $errorMessage }}</p>
    @else
        <ul>
            @foreach($plan as $dayPlan)
                <li class="mb-4">
                    <h3 class="text-xl font-semibold">Day {{ $loop->index + 1 }}: {{ $dayPlan['day'] }}</h3>
                    <p>Visit: {{ $dayPlan['attraction'] }}</p>
                    <p>Lunch at: {{ $dayPlan['restaurant'] }}</p>
                    <p>Walking time: {{ $dayPlan['walking_time'] }}</p>
                    <p>Attraction cost: ${{ $dayPlan['attraction_cost'] }}</p>
                    <p>Restaurant cost: ${{ $dayPlan['restaurant_cost'] }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</div>
