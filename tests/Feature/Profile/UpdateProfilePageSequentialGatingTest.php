<?php

namespace Tests\Feature\Profile;

use App\Livewire\Pages\Profile\UpdateProfilePage;
use App\Models\Course;
use App\Models\Dislike;
use App\Models\Hobby;
use App\Models\School;
use App\Models\User;
use Filament\Forms\Components\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateProfilePageSequentialGatingTest extends TestCase
{
    use RefreshDatabase;

    protected function getSectionByHeading($component, string $heading): Section
    {
        $form = $component->instance()->getForm('form');

        $section = collect($form->getComponents())
            ->first(fn ($schemaComponent): bool => $schemaComponent instanceof Section && (string) $schemaComponent->getHeading() === $heading);

        $this->assertInstanceOf(Section::class, $section, "Section [{$heading}] was not found in the profile form schema.");

        return $section;
    }

    public function test_profile_sections_are_enabled_sequentially_and_relock_when_earlier_state_becomes_incomplete(): void
    {
        $user = User::factory()->create([
            'profile_updated' => false,
        ]);

        $hobby = Hobby::unguarded(fn () => Hobby::query()->create([
            'name' => 'Reading',
        ]));

        $dislike = Dislike::unguarded(fn () => Dislike::query()->create([
            'name' => 'Noise',
        ]));

        $school = School::query()->create([
            'name' => 'Test University',
            'short_name' => 'TU',
            'state' => 'Lagos',
        ]);

        $course = Course::unguarded(fn () => Course::query()->create([
            'name' => 'Computer Science',
            'short_name' => 'CSC',
            'max_level' => '400',
        ]));

        $school->courses()->attach($course->id);

        $this->actingAs($user);

        $component = Livewire::test(UpdateProfilePage::class);

        $general = $this->getSectionByHeading($component, 'General Information');
        $personal = $this->getSectionByHeading($component, 'Personal Information');
        $educational = $this->getSectionByHeading($component, 'Educational Information');
        $apartment = $this->getSectionByHeading($component, 'Apartment Information');

        $this->assertFalse($general->isDisabled());
        $this->assertTrue($personal->isDisabled());
        $this->assertTrue($educational->isDisabled());
        $this->assertTrue($apartment->isDisabled());
        $this->assertStringContainsString('Complete General Information to continue.', (string) $personal->getDescription());
        $this->assertStringContainsString('Complete Personal Information to continue.', (string) $educational->getDescription());
        $this->assertStringContainsString('Complete Educational Information to continue.', (string) $apartment->getDescription());

        $component
            ->set('avatar_image', ['avatars/' . $user->id . '/avatar.jpg'])
            ->set('first_name', 'Jane')
            ->set('last_name', 'Doe');

        $personal = $this->getSectionByHeading($component, 'Personal Information');
        $educational = $this->getSectionByHeading($component, 'Educational Information');
        $apartment = $this->getSectionByHeading($component, 'Apartment Information');

        $this->assertFalse($personal->isDisabled());
        $this->assertTrue($educational->isDisabled());
        $this->assertTrue($apartment->isDisabled());

        $component
            ->set('bio', 'I enjoy reading and value clean shared spaces.')
            ->set('hobbies', [$hobby->id])
            ->set('dislikes', [$dislike->id]);

        $educational = $this->getSectionByHeading($component, 'Educational Information');
        $apartment = $this->getSectionByHeading($component, 'Apartment Information');

        $this->assertFalse($educational->isDisabled());
        $this->assertTrue($apartment->isDisabled());

        $component
            ->set('school', $school->id)
            ->set('course', $course->id)
            ->set('course_level', 200);

        $apartment = $this->getSectionByHeading($component, 'Apartment Information');

        $this->assertFalse($apartment->isDisabled());

        $component->set('school', null);

        $educational = $this->getSectionByHeading($component, 'Educational Information');
        $apartment = $this->getSectionByHeading($component, 'Apartment Information');

        $this->assertFalse($educational->isDisabled());
        $this->assertTrue($apartment->isDisabled());
        $this->assertStringContainsString('Complete Educational Information to continue.', (string) $apartment->getDescription());
    }
}
