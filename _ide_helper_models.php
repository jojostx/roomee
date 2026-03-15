<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property array<array-key, mixed> $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppSetting whereValue($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAppSetting {}
}

namespace App\Models{
/**
 * @property int $blocker_id
 * @property int $blockee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $id
 * @property-read \App\Models\User $blockee
 * @property-read \App\Models\User $blocker
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blocklist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blocklist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blocklist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blocklist whereBlockeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blocklist whereBlockerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blocklist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blocklist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blocklist whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBlocklist {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperContact {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $type
 * @property string $link
 * @property bool $is_enabled
 * @property array<array-key, mixed>|null $metadata
 * @property string|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ContactChannelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactChannel whereVerifiedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperContactChannel {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $short_name
 * @property string $max_level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $levels
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\School> $schools
 * @property-read int|null $schools_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereMaxLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCourse {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dislike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dislike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dislike query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dislike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dislike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dislike whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dislike whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dislike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dislike whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDislike {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $faq_category_id
 * @property string $question
 * @property string $answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FaqCategory $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereFaqCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFaq {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Faq> $faqs
 * @property-read int|null $faqs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFaqCategory {}
}

namespace App\Models{
/**
 * @property int $favoriter_id
 * @property int $favoritee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $id
 * @property-read \App\Models\User $favoritee
 * @property-read \App\Models\User $favoriter
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereFavoriteeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereFavoriterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFavorite {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $feedback
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFeedback {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hobby newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hobby newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hobby query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hobby whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hobby whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hobby whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hobby whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hobby whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hobby whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperHobby {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $address
 * @property string $city
 * @property int $rent_amount
 * @property string $rent_period
 * @property \Illuminate\Support\Carbon $move_in_date
 * @property array<array-key, mixed>|null $amenities
 * @property array<array-key, mixed>|null $house_rules
 * @property array<array-key, mixed>|null $images
 * @property bool $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ListingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing forAdvancedPreferences(\App\Models\User $seeker)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing forBudgetAndTimeline(\App\Models\User $seeker)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing forDealbreakers(\App\Models\User $seeker)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereAmenities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereHouseRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereMoveInDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereRentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereRentPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Listing whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperListing {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $user_type
 * @property int $user_id
 * @property string $email
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUserEmail forUser(\Illuminate\Database\Eloquent\Model $user)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUserEmail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUserEmail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUserEmail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUserEmail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUserEmail whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUserEmail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUserEmail whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUserEmail whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUserEmail whereUserType($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPendingUserEmail {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $description
 * @property int $severity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReport {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $uuid
 * @property int $sender_id
 * @property int $recipient_id
 * @property \App\Enums\RoommateRequestStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $recipient
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest accepted()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest betweenModels(\App\Models\User $sender, \App\Models\User $recipient)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest denied()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest status(\App\Enums\RoommateRequestStatus|string $status = '')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest whereRecipient(\App\Models\User $model)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest whereSender(\App\Models\User $model)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoommateRequest whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRoommateRequest {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $short_name
 * @property string $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Town> $towns
 * @property-read int|null $towns_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\SchoolFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|School whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSchool {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\School> $School
 * @property-read int|null $school_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Town newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Town newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Town query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Town whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Town whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Town whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Town whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Town whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Town whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTown {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property \App\Enums\UserRole $role
 * @property bool $is_premium
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
 * @property string|null $identity_document_path
 * @property string|null $selfie_path
 * @property \App\Enums\VerificationStatus $verification_status
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $verification_submitted_at
 * @property bool $is_suspended
 * @property \Illuminate\Support\Carbon|null $suspended_at
 * @property string|null $suspension_reason
 * @property string|null $rooms
 * @property array<array-key, mixed>|null $settings
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $blockers
 * @property-read int|null $blockers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $blocklists
 * @property-read int|null $blocklists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactChannel> $contactChannels
 * @property-read int|null $contact_channels_count
 * @property-read \App\Models\Course|null $course
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dislike> $dislikes
 * @property-read int|null $dislikes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $favorites
 * @property-read int|null $favorites_count
 * @property-read string $avatar_path
 * @property-read string $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hobby> $hobbies
 * @property-read int|null $hobbies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Listing> $listings
 * @property-read int|null $listings_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $receivedRoommateRequests
 * @property-read int|null $received_roommate_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * @property-read \App\Models\School|null $school
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $sentRoommateRequests
 * @property-read int|null $sent_roommate_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Town> $towns
 * @property-read int|null $towns_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $allPotentialRoommates
 * @property-read int|null $all_potential_roommates_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User betweenModels(\App\Models\User $sender, \App\Models\User $recipient)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User excludeUser($user_id)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User forAdvancedListingPreferences(?\App\Models\User $seeker = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User forBudgetAndTimeline(?\App\Models\User $seeker = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User forDealbreakers(?\App\Models\User $seeker = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User forSimilarityBudgetOverlap(?\App\Models\User $subject = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User gender($gender)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User school($school_id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User validNonBlockedByUsers(?\App\Models\User $subject = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User validNonBlockedUsers(?\App\Models\User $subject = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User validNonBlockingUsers(?\App\Models\User $subject = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User validSimilarityCandidates(?\App\Models\User $subject = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User validUsers(?\App\Models\User $subject = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCourseLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdentityDocumentPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsPremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsSuspended($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMaxBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMinBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNotUuid($uuid, $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfileUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSchoolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSelfiePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSuspendedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSuspensionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVerificationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVerificationSubmittedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

