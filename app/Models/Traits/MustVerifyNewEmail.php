<?php

namespace App\Models\Traits;

use App\Mail\VerifyNewEmail;
use App\Models\PendingUserEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

trait MustVerifyNewEmail
{
  /**
   * Deletes all previous attempts for this user, creates a new model/token
   * to verify the given email address and send the verification URL
   * to the new email address.
   *
   * @param string $email
   * @param callable $withMailable
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function newEmail(string $email, callable $withMailable = null): ?Model
  {
    if ($this->getEmailForVerification() === $email && $this->hasVerifiedEmail()) {
      return null;
    }

    return $this->createPendingUserEmailModel($email)->tap(function ($model) use ($withMailable) {
      $this->sendPendingEmailVerificationMail($model, $withMailable);
    });
  }

  /**
   * returns the model to use as PendingUserModel model.
   *
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function getEmailVerificationModel(): Model
  {
    return app(PendingUserEmail::class);
  }

  /**
   * Creates new PendingUserModel model for the given email.
   *
   * @param string $email
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function createPendingUserEmailModel(string $email): Model
  {
    $this->clearPendingEmail();

    $token = hash_hmac('sha256', str()->random(40), $this->getKey());

    return $this->getEmailVerificationModel()->create([
      'user_type' => get_class($this),
      'user_id'   => $this->getKey(),
      'email'     => $email,
      'token'     => $token,
    ]);
  }

  /**
   * Returns the pending email address.
   *
   * @return string|null
   */
  public function getPendingEmail(): ?string
  {
    return $this->getEmailVerificationModel()->forUser($this)->value('email');
  }

  /**
   * Deletes the pending email address models for this user.
   *
   * @return void
   */
  public function clearPendingEmail()
  {
    $this->getEmailVerificationModel()->forUser($this)->get()->each->delete();
  }

  /**
   * Sends the VerifyNewEmail Mailable to the new email address.
   *
   * @param \Illuminate\Database\Eloquent\Model $pendingUserEmail
   * @param callable $withMailable
   * @return mixed
   */
  public function sendPendingEmailVerificationMail(Model $pendingUserEmail, callable $withMailable = null)
  {
    $mailable = new VerifyNewEmail($pendingUserEmail);

    if ($withMailable) {
      $withMailable($mailable, $pendingUserEmail);
    }

    return Mail::to($pendingUserEmail->email)->send($mailable);
  }

  /**
   * Grabs the pending user email address, generates a new token and sends the Mailable.
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function resendPendingEmailVerificationMail(): ?Model
  {
    $pendingUserEmail = $this->getEmailVerificationModel()->forUser($this)->firstOrFail();

    return $this->newEmail($pendingUserEmail->email);
  }
}
