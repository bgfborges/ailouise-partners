# ChangeLog

## 1.5.7
* FIX: Date slots in admin panel

## 1.5.6
* FIX: Labels of statuses in the admin panel

## 1.5.5
* ADD: Compatibility with JetFormBuilder
* FIX: Saving global settings
* FIX: Empty days of weeks in the schedule
* FIX: The price from the calculator field is transferred to the WooCommerce prices
* FIX: Fixed a bug with booking different times with the same provider


## 1.5.1
* FIX: Time slots compatibility with php 7.1

## 1.5.0
* ADD: Multi booking
* ADD: Provider price
* UPD: Integration with woocommerce
* UPD: Notifications `Send Email` and `Call a Webhook`

## 1.4.0
* UPD: Admin panel for appointments
* ADD: Ability to edit and add appointments from the admin panel
* ADD: New views of appointments calendar and timeline.
* ADD: Filter for searching and sorting appointments in the admin panel

## 1.3.3
* FIX: Webhook date data

## 1.3.2
* FIX: Fixed appointment if option "Manage Capacity" is enabled

## 1.3.1
* FIX: Custom Schedule in services and providers

## 1.3.0
* ADD: Plugin settings have been moved to the Crocoblock dashboard
* ADD: Added the Slot Duration, Buffer Before Slot, Buffer After Slot of service and provider in the listing settings
* FIX: Display the name of service and provider in the admin panel if the service service or provider is private or with a password.

## 1.2.6
* ADD: New macros: `%service_link%` `%provider_link%` `%appointment_start%` `%appointment_end%`
* UPD: Timing control for options: Duration, Buffer Time Before Slot, Buffer Time After Slot
* UPD: If the date is fully booked, the `.jet-apb-calendar-date-disabled` class is added to it

## 1.2.5
* UPD: Change edit permissions

## 1.2.4
* FIX: Booking time error in WC details

## 1.2.3
* UPD: Added localization file

## 1.2.2
* FIX: WC product creation

## 1.2.1
* FIX: Saving custom schedule settings in services without a selected provider.

## 1.2.0
* ADD: Added the ability to select the period of working days and days off;
* ADD: Added Custom Schedule for single services and providers;
* ADD: Allow to add appointments details to WooCommerce orders;
* ADD: Added new macros for form email notification %service_title%, %provider_title%;
* ADD: Allow ability for users to add a appointment to their calendar;
* FIX: Fixed minor bugs.

## 1.1.1
* UPD: allow to correctly render appointment form on Ajax;
* UPD: allow to manage DB columns;
* FIX: disable next page button if time slot not selected in the calendar;
* FIX: providers REST API endpoint.

## 1.1.0
* ADD: Allow toi showcase appointments with Listing Grid  widget;
* ADD: Services capacity management;
* ADD: Allow to set custom labels for week days and months;
* ADD: Booking details to WooCommerce order e-mails;
* UPD: Allow to change time format in the calendar slots;
* UPD: Allow to use custom templates for providers select;
* UPD: Allow to correctly use radio field as services select;
* FIX: Appointment date format for e-mail;

## 1.0.0
* Initial release
