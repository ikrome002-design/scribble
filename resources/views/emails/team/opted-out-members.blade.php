<x-mail::message>
Dear $name,

We hope this message finds you well. We want to inform you about the recent
truncation of Team Link membership in your merchant account, as you had scaled
down the number of members without removing the current members to match the
newly set Scale Down settings.
Please review the following details:
Truncation Details:

<x-mail::panel>
Total Team Link members before truncation: {{$previous}}
Newly set Scale Down settings: {{$current}}
</x-mail::panel>

The following Team Link members have been opted out and will no longer able to access your
account as from tommorrow:

@foreach($members as $m)
****
Image: <img style='width:80px;height:auto' src="{{ $message->embed('/private/staff/'.$m->image}}?token='{{encryptDecryptUserData('encrypt', json_encode(['id'=>$m->id]))}}"><br>
Unique ID: {{$m->unique_id}}<br>
First Name: {{$m->fname}}<br>
Last Name: {{$m->lname}}<br>
Email Address: {{$m->lname}}<br>
Phone Number: {{$m->lname}}<br>
Role: {{$m->team_role ?? $m->role}}<br>
@endforeach

Please note that the truncation process randomly removed Team Link members to
match the newly set Scale Down settings. This action was necessary to align your
Team Link membership with the desired configuration.

We apologize for any inconvenience caused by this truncation. If you have an y
concerns or require further assistance, please don't hesitate to reach out to our
support team. We are available to help you through this process.
Thank you for your understanding, and we appreciate your continued trust and
support

Best regards,
Citrus Labs Team
</x-mail::message>
