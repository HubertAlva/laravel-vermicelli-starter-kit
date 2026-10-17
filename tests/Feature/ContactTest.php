<?php

use App\Data\ContactData;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;

test('the contact page can be rendered', function () {
    $this->get(route('contact.show'))->assertOk();
});

test('contact messages are queued to the configured address', function () {
    Mail::fake();
    config(['mail.to.address' => 'inbox@example.com']);

    $this->post(route('contact.store'), [
        'name' => 'Jane',
        'email' => 'jane@example.com',
        'subject' => 'Hello',
        'message' => 'I have a question.',
    ])
        ->assertRedirect()
        ->assertInertiaFlash('toast.type', 'success');

    Mail::assertQueued(ContactMessage::class, fn (ContactMessage $mail) => $mail->hasTo('inbox@example.com')
        && $mail->hasReplyTo('jane@example.com')
        && $mail->hasSubject('Hello'));
});

test('contact messages require a name, email and message', function () {
    Mail::fake();

    $this->post(route('contact.store'), ['email' => 'not-an-email'])
        ->assertSessionHasErrors(['name', 'email', 'message']);

    Mail::assertNothingQueued();
});

test('the contact email escapes user content', function () {
    $html = (new ContactMessage(new ContactData(
        name: 'Jane',
        email: 'jane@example.com',
        subject: null,
        message: "<script>alert('xss')</script>",
    )))->render();

    expect($html)
        ->toContain('&lt;script&gt;')
        ->not->toContain("<script>alert('xss')</script>");
});
