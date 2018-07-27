// // Convert timestamp or Date object to localized Date object
// function Datey(date) {
//     // timestamp should be in milliseconds
//     const timestamp    = convertToMs(date);
//     const offset       = getOffset(timestamp);
//     const adjustedDate = timestamp + offset;
//
//     return msToDate(adjustedDate);
// }
//
// // Find hour difference from UTC to the client's reported timezone in ms
// function getOffset(dateObject) {
//     if(!(dateObject instanceof Date)) {
//         dateObject = msToDate(dateObject);
//     }
//
//     return -(dateObject.getTimezoneOffset() * 60 * 1000);
// }
//
// // Safely convert a millisecond value into a Date object
// function msToDate(ms) {
//     if(isNaN(ms)) {
//         throw new TypeError("msToDate only accepts numbers");
//     }
//
//     if(ms / 1000 < 1) {
//         throw new RangeError("msToDate requires that ms be in milliseconds (>= 1000)");
//     }
//
//     return new Date(ms);
// }
//
// function convertToMs(timestamp) {
//     if(!isNaN(timestamp)) {
//         if(timestamp / 1000 < 1) {
//             throw new RangeError("convertToMs requires that timestamp be in milliseconds (>= 1000)");
//         }
//
//         return timestamp;
//     }
//
//     else if(timestamp instanceof Date) {
//         return timestamp.getTime();
//     }
//
//     else {
//         throw new TypeError("timestamp is in an unsupported format");
//     }
// }
//
// const FULL_DATE          = 0;
// const MONTH_DAY          = 1;
// const MONTH_DAY_RELATIVE = 2;
// const TODAY              = 3;
//
const dayBundle = {
    keys: ['en-us', 'en', 'en-gb'],
    obj: {
        full: [
            "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday",
            "Friday", "Saturday"
        ],
        short: [
            "Sun", "Mon", "Tue", "Wed", "Thur", "Fri", "Sat"
        ]
    }
};

const monthBundle = {
    keys: ['en-us', 'en', 'en-gb'],
    obj: {
        full: [
            "January", "February", "March", "April", "May", "June", "July",
            "August", "September", "October", "November", "December"
        ],
        short: [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep",
            "Oct", "Nov", "Dec"
        ]
    }
};


const dayNames   = { locale: fooToCoo(dayBundle) };
const monthNames = { locale: fooToCoo(monthBundle) };

function fooToCoo(foo) {
    const { keys, obj } = foo;
    let temp = {};
    keys.forEach((key) => {
        temp[key] = obj;
    });

    return temp;
}
//
// function formatDate(timestamp, is24HourTime = true, locale = "en") {
//     const date = !(timestamp instanceof Date) ? msToDate(timestamp) : timestamp;
//     const month = date.getMonth();
//     const monthShort = monthNames.locale[locale].short[month];
//
//     const day = date.getDay();
//     const dayShort = dayNames.locale[locale].short[day];
//
//     const dated = date.getDate();
//
//     const year = date.getFullYear();
//     const offset = Math.ceil((new Date().getTime() - date.getTime()) / 1000 / 60 / 60 / 24);
//     const ymd = locale === "en-us" ? `${month + 1}/${dated}/${year}` : `${dated}/${month + 1}/${year}`
//     let minutes = date.getMinutes();
//     minutes = minutes > 0 ? minutes : "00"
//     let hour = date.getHours();
//     let time = `${hour}:${minutes}`;
//     if(!is24HourTime) {
//         if(hour > 12) {
//             time = `${hour - 12}:${minutes} pm`;
//         }
//         else if(hour === 12) {
//             time = `${hour}:${minutes} pm`;
//         }
//         else if(hour === 0) {
//             time = `12:${minutes} am`;
//         }
//         else {
//             time += " am";
//         }
//     }
//
//     console.log(`${dayShort}, ${monthShort} ${dated}, ${year} at ${time}`);
//     console.log(`${monthShort} ${dated} (${offset} days ago)`);
//     console.log(`${time}`);
//     console.log(`${monthShort} ${dated}`);
//     console.log(ymd);
//     // Tue, Jul 24, 2018 at 1:30 PM
//     // Jul 12 (12 days ago)
//     // 1:33 pm
//     // Jul 23
//     // 12/21/17
// }

class DateConverter {
    constructor(timestamp, is24HourTime = false) {
        if(typeof timestamp === 'undefined') {
            throw new Error("DateConverter must be provided a timestamp");
        }

        this.is24HourTime = typeof is24HourTime === "boolean"
            ? is24HourTime
            : false;
        this.daysSince    = 0;
        this.year         = 0;
        this.dated        = "";
        this.month        = { str:{ full:"", short:"" }, int:0 };
        this.day          = { str:{ full:"", short:"" }, int:0 };

        this.setDate(timestamp);
        this.locale       = this.getLocale();
    }

    getDate() {
        return this.date;
    }

    setDate(date) {
        if(!(date instanceof Date)) {
            this.date = this._msToDate(date);
            return;
        }

        this.date = date;
        // this.processDate();
        return;
    }

    getLocale() {
        const { language, browserLanguage, languages } = navigator;
        if(typeof this.locale !== "undefined") {
            return this.locale;
        }

        if(language) {
            return language;
        }
        else if(languages) {
            return languages[0];
        }
        else if(browserLanguage) {
            return browserLanguage;
        }
        else {
            return "en";
        }
    }

    getDateMs() {
        return this.date.getTime();
    }

    // Safely convert a millisecond value into a Date object
    _msToDate(ms) {
        if(typeof ms !== 'number' || isNaN(ms)) {
            throw new TypeError("_msToDate only accepts numbers");
        }

        if(ms / 1000 < 1) {
            throw new RangeError("_msToDate requires that ms be in milliseconds (>= 1000)");
        }

        return new Date(ms);
    }

    // Find hour difference from UTC to the client's reported timezone in ms
    getOffset() {
        const now = new Date();
        return -(now.getTimezoneOffset() * 60 * 1000);
    }

    // Store various parts of the Date object in state
    // processDate() {
    //     const { date, locale } = this;
    //     // Year
    //     this.year = date.getFullYear();
    //     // Month
    //     this.month.int       = date.getMonth();
    //     this.month.str.full  = monthNames.locale[locale].full[this.month];
    //     this.month.str.short = monthNames.locale[locale].short[this.month];
    //     // Day
    //     this.day.int       = date.getDay();
    //     this.day.str.full  = dayNames.locale[locale].full[day];
    //     this.day.str.short = dayNames.locale[locale].short[day];
    //     // Date
    //     this.dated = date.getDate();
    //     // Offset
    //     this.daysSince = this._calculateTimeSinceDate();
    //     // Time
    //     this.minutes = date.getMinutes();
    //     const hour = date.getHours();
    //     if(!this.is24HourTime) {
    //         if(hour > 12) {
    //             this.hour = hour - 12;
    //         }
    //         else {
    //             this.hour = hour;
    //         }
    //     }
    //     else {
    //         this.hour = hour;
    //     }
    //
    //     return;
    // }

    _calculateTimeSinceDate() {
        const now = new Date();
        const deltaMs = now.getTime() - this.getDateMs();
        const seconds = Math.floor(deltaMs / 1000);
        const units = {
            second: 1,
            minute: 60,
            hour: 3600,
            day: 86400,
            MIN_SECONDS: 30,
            MAX_DAYS: 30,
            MAX_HOURS: 7
        };
        // If there is less than 1 second or less than MIN_SECONDS
        if(seconds < units.second || seconds < units.MIN_SECONDS) {
            // Just now
            return 0;
        }
        // If there is less than 60 seconds
        else if(seconds < units.minute) {
            // Seconds ago
            return { seconds };
        }
        // If there is less than 60 minutes
        else if(seconds < units.hour) {
            // Minutes ago
            const minutes = Math.floor(seconds / units.minute);
            return { minutes };
        }
        // If there is less than 24 hours
        else if(seconds < units.day) {
            // Hours ago
            const hours = Math.floor(seconds / units.hour);
            if(hours > units.MAX_HOURS) {
                const remainder = seconds % units.hour;
                const minutes   = Math.floor(remainder / units.minute);
                return { hours, minutes };
            }

            return { hours };
        }
        // If there is less than or there is 30 days
        else if(seconds <= (units.day * units.MAX_DAYS)) {
            // Days ago
            const days = Math.floor(seconds / units.day);
            return { days };
        }
        // Otherwise, this property will not be used
        else {
            return false;
        }
    }

    _roundUpToHundredths(n) {
        return Math.round(n * 100) / 100;
    }
}

export default DateConverter;
