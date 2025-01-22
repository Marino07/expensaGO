<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name }}</title>
</head>

<body
    style="background: linear-gradient(135deg, #f4f6f9 0%, #aec8e7 100%); font-family: Arial, sans-serif; margin: 0; padding: 10px;">
    <table
        style="max-width: 650px; width: 100%; margin: 10px auto; background-color: #a2c9f9; border-radius: 24px; overflow: hidden; box-shadow: 0 15px 30px -12px rgba(0, 0, 0, 0.1);"
        cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding: 0;">
                <!-- Logo only at the top -->
                {!! App\View\Components\EmailLogo::render() !!}
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 32px; text-align: center;">
                <div
                    style="background: linear-gradient(135deg, #a9d0f2 0%, #dbeafe 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px;">
                    <h2 style="font-size: 20px; color: #1e40af; margin: 0 0 15px 0;">Hi, {{ $user->name }}! 👋</h2>
                    <p style="font-size: 16px; color: #334155; line-height: 1.5;">There is new event in the town,
                        maybe you are interest?</p>

                    <!-- Simplified event name presentation -->
                    <div style="margin-top: 12px; text-align: center;">
                        <h3 style="font-size: 20px; color: #3095c3; margin: 0; font-weight: 600; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 8px;">
                            {{ $event->name }}
                            <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; padding-left: 7px; vertical-align: middle;">
                                <path d="M3 8C2.44772 8 2 8.44772 2 9V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V9C22 8.44772 21.5523 8 21 8H3Z" fill="#4296FF"/>
                                <path d="M7 2C7.55228 2 8 2.44772 8 3V4H16V3C16 2.44772 16.4477 2 17 2C17.5523 2 18 2.44772 18 3V4.10002C20.2822 4.56329 22 6.58104 22 9C22 9.55228 21.5523 10 21 10H3C2.44772 10 2 9.55228 2 9C2 6.58104 3.71776 4.56329 6 4.10002V3C6 2.44772 6.44772 2 7 2Z" fill="#152C70"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7 13C7 12.4477 7.44772 12 8 12H16C16.5523 12 17 12.4477 17 13C17 13.5523 16.5523 14 16 14H8C7.44772 14 7 13.5523 7 13Z" fill="#152C70"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7 17C7 16.4477 7.44772 16 8 16H12C12.5523 16 13 16.4477 13 17C13 17.5523 12.5523 18 12 18H8C7.44772 18 7 17.5523 7 17Z" fill="#152C70"/>
                            </svg>
                        </h3>
                    </div>

                    <!-- Event image moved here -->
                    <div style="margin-top: 20px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                        <img src="{{ $event->image_url }}" alt="Event banner" style="width: 100%; height: auto; max-height: 250px; object-fit: cover; display: block;">
                    </div>
                </div>

                <table
                    style="width: 100%; background-color: #c7e1fa; border-radius: 16px; padding: 20px; margin-bottom: 24px; border: 1px solid #e2e8f0;"
                    cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding: 16px;">
                            <table style="width: 100%;" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                            <tr>
                                                <td width="40" style="padding-right: 12px;">
                                                    <div style="background-color: #bfdbfe; border-radius: 50%; width: 40px; height: 40px; text-align: center; line-height: 40px; font-size: 20px;">
                                                        💰
                                                    </div>
                                                </td>
                                                <td style="text-align: left;">
                                                    <div style="font-size: 14px; color: #64748b;">Price</div>
                                                    <div style="font-size: 16px; color: #0f172a; font-weight: 600;">
                                                        @if($event->free)
                                                            Free Event
                                                        @elseif($event->price)
                                                            €{{ $event->price }}
                                                        @elseif($event->price_min && $event->price_max)
                                                            €{{ $event->price_min }} - €{{ $event->price_max }}
                                                        @else
                                                            Price not available
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                            <tr>
                                                <td width="40" style="padding-right: 12px;">
                                                    <div style="background-color: #bfdbfe; border-radius: 50%; width: 40px; height: 40px; text-align: center; line-height: 40px; font-size: 20px;">
                                                        📅
                                                    </div>
                                                </td>
                                                <td style="text-align: left;">
                                                    <div style="font-size: 14px; color: #64748b;">Date & Time</div>
                                                    <div style="font-size: 16px; color: #0f172a; font-weight: 600;">{{ $formattedDate }}</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                            <tr>
                                                <td width="40" style="padding-right: 12px;">
                                                    <div style="background-color: #bfdbfe; border-radius: 50%; width: 40px; height: 40px; text-align: center; line-height: 40px; font-size: 20px;">
                                                        📍
                                                    </div>
                                                </td>
                                                <td style="text-align: left;">
                                                    <div style="font-size: 14px; color: #64748b;">Location</div>
                                                    <div style="font-size: 16px; color: #0f172a; font-weight: 600;">{{ $event->location }}</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- New CTA Section -->
                <table cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 24px;">
                    <tr>
                        <td style="text-align: center; padding: 10px 0;">
                            <a href="{{ $actionUrl }}"
                               style="display: inline-block;
                                      background: linear-gradient(135deg, #3095c3 0%, #9cc3e5 100%);
                                      color: white;
                                      text-decoration: none;
                                      padding: 14px 28px;
                                      border-radius: 50px;
                                      font-size: 15px;
                                      font-weight: 500;
                                      letter-spacing: 0.3px;
                                      box-shadow: 0 4px 15px rgba(49, 149, 195, 0.2);
                                      border: 1px solid rgba(255, 255, 255, 0.15);
                                      transition: all 0.3s ease;">
                                <span style="display: inline-flex; align-items: center; gap: 6px;">
                                    See more details
                                    <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; margin-left: 4px;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5l7 7-7 7"></path>
                                    </svg>
                                </span>
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="background: linear-gradient(135deg, #789ae4 0%, #576cb1 100%); border-bottom-left-radius: 24px; border-bottom-right-radius: 24px; padding: 24px; text-align: center;">
                <p style="color: white; margin-bottom: 8px; font-size:16px; font-style: italic; font-weight: 500;">Plan your journey with ease ✈️ </p>
                <p style="color: #bfdbfe; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
            </td>
        </tr>
    </table>
</body>

</html>
