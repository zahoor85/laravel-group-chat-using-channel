
# Laravel Group Chat Using Laravel Channel

A Group Chat application using laravel websockets and Pusher the UI is not that much fancy but the idea has been implemented. For authentication Laravel Breez has been used. The application focus on the following.

# Group Creation:

Super Admin can create groups.

Each group can have multiple channels.

# Channel Management:

Admins can create channels within groups.

Channels allow users to focus on specific topics or discussions.

# Membership and Permissions:

Admins can assign members to groups.

Permissions can be set for each channel, controlling who can join, post messages, or view messages.


# Laravel Websockets
https://beyondco.de/docs/laravel-websockets/getting-started/introduction

# Pusher
https://pusher.com/

# Configuration

In termianl visit the project root directory and run following cammands:

Install dependencies

`composer insatll`

Run Migration

`php artisan migrate`

Seed DB it will create a Super Admin Account `super@super.com` Password is `password`

`php artisan db:seed`

Install npm and it's dependencies

`npm insatll`

One all this done. Apply this command to run the vite server

`npm run dev`

In another termianl window run

`php artisan serve`

As we are using Larvel websockets we also need to run the websockets server as well:

`php artisan websockets:serve`
