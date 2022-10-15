<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Blocklist
 *
 * @property int $blocker_id
 * @property int $blockee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Blocklist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocklist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocklist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocklist whereBlockeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocklist whereBlockerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocklist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocklist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocklist whereUuid($uuid, $uuidColumn = null)
 */
	class IdeHelperBlocklist {}
}

namespace App\Models{
/**
 * App\Models\Contact
 *
 * @property int $id
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUuid($value)
 */
	class IdeHelperContact {}
}

namespace App\Models{
/**
 * App\Models\Course
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $short_name
 * @property string $max_level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\School[] $schools
 * @property-read int|null $schools_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereMaxLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUuid($value)
 */
	class IdeHelperCourse {}
}

namespace App\Models{
/**
 * App\Models\Dislike
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Dislike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dislike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dislike query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dislike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dislike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dislike whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dislike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dislike whereUuid($value)
 */
	class IdeHelperDislike {}
}

namespace App\Models{
/**
 * App\Models\Faq
 *
 * @property int $id
 * @property string $uuid
 * @property int $faq_category_id
 * @property string $question
 * @property string $answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FaqCategory $category
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereFaqCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereUuid($value)
 */
	class IdeHelperFaq {}
}

namespace App\Models{
/**
 * App\Models\FaqCategory
 *
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Faq[] $faqs
 * @property-read int|null $faqs_count
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory whereUuid($value)
 */
	class IdeHelperFaqCategory {}
}

namespace App\Models{
/**
 * App\Models\Favorite
 *
 * @property int $favoriter_id
 * @property int $favoritee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereFavoriteeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereFavoriterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereUpdatedAt($value)
 */
	class IdeHelperFavorite {}
}

namespace App\Models{
/**
 * App\Models\Feedback
 *
 * @property int $id
 * @property string $uuid
 * @property int $feedback
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUuid($value)
 */
	class IdeHelperFeedback {}
}

namespace App\Models{
/**
 * App\Models\Hobby
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Hobby newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Hobby newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Hobby query()
 * @method static \Illuminate\Database\Eloquent\Builder|Hobby whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hobby whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hobby whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hobby whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hobby whereUuid($value)
 */
	class IdeHelperHobby {}
}

namespace App\Models{
/**
 * App\Models\Report
 *
 * @property int $id
 * @property string $uuid
 * @property string $description
 * @property int $severity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUuid($value)
 */
	class IdeHelperReport {}
}

namespace App\Models{
/**
 * App\Models\RoommateRequest
 *
 * @property string $id
 * @property string $uuid
 * @property int $sender_id
 * @property int $recipient_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $recipient
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest betweenModels(\App\Models\User $sender, \App\Models\User $recipient)
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest whereRecipient(\App\Models\User $model)
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest whereSender(\App\Models\User $model)
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoommateRequest whereUuid($value)
 */
	class IdeHelperRoommateRequest {}
}

namespace App\Models{
/**
 * App\Models\School
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $short_name
 * @property string $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Town[] $towns
 * @property-read int|null $towns_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\SchoolFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|School newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|School newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|School query()
 * @method static \Illuminate\Database\Eloquent\Builder|School whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereUuid($value)
 */
	class IdeHelperSchool {}
}

namespace App\Models{
/**
 * App\Models\Town
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\School[] $School
 * @property-read int|null $school_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Town newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Town newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Town query()
 * @method static \Illuminate\Database\Eloquent\Builder|Town whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Town whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Town whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Town whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Town whereUuid($value)
 */
	class IdeHelperTown {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $profile_updated
 * @property string|null $bio
 * @property int $course_level
 * @property int|null $max_budget
 * @property int|null $min_budget
 * @property int|null $school_id
 * @property int|null $course_id
 * @property string|null $avatar
 * @property string|null $cover_photo
 * @property string|null $rooms
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $blockers
 * @property-read int|null $blockers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $blocklists
 * @property-read int|null $blocklists_count
 * @property-read \App\Models\Course|null $course
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Dislike[] $dislikes
 * @property-read int|null $dislikes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $favorites
 * @property-read int|null $favorites_count
 * @property-read mixed $avatar_path
 * @property-read mixed $cover_photo_path
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Hobby[] $hobbies
 * @property-read int|null $hobbies_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $recievedRoommateRequests
 * @property-read int|null $recieved_roommate_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Report[] $reports
 * @property-read int|null $reports_count
 * @property-read \App\Models\School|null $school
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $sentRoommateRequests
 * @property-read int|null $sent_roommate_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Town[] $towns
 * @property-read int|null $towns_count
 * @method static \Illuminate\Database\Eloquent\Builder|User betweenModels(\App\Models\User $sender, \App\Models\User $recipient)
 * @method static \Illuminate\Database\Eloquent\Builder|User excludeUser($user_id)
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User gender($gender)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User school($school_id)
 * @method static \Illuminate\Database\Eloquent\Builder|User validNonBlockedByUsers()
 * @method static \Illuminate\Database\Eloquent\Builder|User validNonBlockedUsers()
 * @method static \Illuminate\Database\Eloquent\Builder|User validNonBlockingUsers()
 * @method static \Illuminate\Database\Eloquent\Builder|User validUsers()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCourseLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCoverPhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaxBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMinBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfileUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSchoolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUuid($value)
 */
	class IdeHelperUser {}
}

