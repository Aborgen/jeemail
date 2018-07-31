import DateConverter from '../DateConverter.js';

describe('Class DateConverter --', () => {
    describe('it throws' , () => {
        test('an Error if no timestamp is provided in the constructor', () => {
            expect(() => new DateConverter()).toThrow(Error);
        });

        test('a TypeError if timestamp is not a number', () => {
            expect(() => new DateConverter(NaN)).toThrow(TypeError);
            expect(() => new DateConverter('not a number')).toThrow(TypeError);
            expect(() => new DateConverter({})).toThrow(TypeError);
            expect(() => new DateConverter([])).toThrow(TypeError);
        });

        test('a RangeError if timestamp is not in milliseconds', () => {
            expect(() => new DateConverter(999)).toThrow(RangeError);
        });
    });

    describe('in state', () => {
        const now          = new Date();
        const is24HourTime = Math.random() < 0.5;
        const converter    = new DateConverter(now, is24HourTime);

        test('it sets Date object from provided Date object', () => {
            expect(converter.getEra()).toBeInstanceOf(Date);
        });

        test('it sets Date object from provided timestamp', () => {
            const nowMs      = now.getTime();
            const converter2 = new DateConverter(nowMs);
            expect(converter2.getEra()).toBeInstanceOf(Date);
        });

        test('it sets locale from the client\'s browser', () => {
            // If navigator properties language and languages are undefined, fall
            // back to 'en' string. This is a compressed version of how
            // DateConverter does it, by the way.
            const locale = navigator.language || (navigator.languages || ["en"])[0];
            expect(converter.getLocale()).toEqual(locale);
        });

        test('it determines whether or not the client uses 24-hour time ' +
             'based on the second argument in the constructor' , () => {
                expect(converter.is24HourTime).toEqual(is24HourTime);
        });
    });

    describe('the "private" method:', () => {
        const now = new Date();
        describe('_msToDate', () => {
            const converter = new DateConverter(now);

            test('safely converts a number >= 1000 into a Date object', () => {
                expect(converter._msToDate(1000)).toBeInstanceOf(Date);
            });

            test('throws a RangeError if provided a number ' +
                 'less than 1000', () => {
                expect(() => converter._msToDate(999)).toThrow(RangeError);
            });

            test('throws a TypeError if not provided a number', () => {
                expect(() => converter._msToDate(NaN)).toThrow(TypeError);
                expect(() => converter._msToDate('not a number')).toThrow(TypeError);
                expect(() => converter._msToDate({})).toThrow(TypeError);
                expect(() => converter._msToDate([])).toThrow(TypeError);
            });
        });

        describe('_calculateTimeSinceEra', () => {
            const nowMs     = now.getTime();
            const converter = new DateConverter(nowMs);
            const units = DateConverter.DATE_UNITS;

            test('return 0 if time elapsed since provided era is less '+
                 'than ' + DateConverter.MIN_SECONDS + ' seconds', () => {
                expect(converter._calculateTimeSinceEra()).toEqual(0)
            });

            test('return { seconds } if time elapsed is less than ' +
                 '60 seconds', () => {
                const pastDate = nowMs - (35 * units.second);
                converter.setEra(pastDate);
                expect(converter._calculateTimeSinceEra()).toEqual(
                    expect.objectContaining({ seconds: 35 })
                );
            });

            test('return { minutes } if time elapsed is less than ' +
                 '60 minutes', () => {
                const pastDate = nowMs - (35 * units.minute);
                converter.setEra(pastDate);
                expect(converter._calculateTimeSinceEra()).toEqual(
                    expect.objectContaining({ minutes: 35 })
                );
            });

            test('return { hours } if time elapsed is less ' +
                 'than 24 hours', () => {
                const pastDate = nowMs - (4 * units.hour);
                converter.setEra(pastDate);
                expect(converter._calculateTimeSinceEra()).toEqual(
                    expect.objectContaining({ hours: 4 })
                );
            });

            test('return { days } if time elapsed is less ' +
                 'than ' + DateConverter.MAX_DAYS + ' days', () => {
                const pastDate = nowMs - (10 * units.day);
                converter.setEra(pastDate);
                expect(converter._calculateTimeSinceEra()).toEqual(
                    expect.objectContaining({ days: 10 })
                );
            });

            test('otherwise, return false', () => {
                const epoch = new Date(0);
                converter.setEra(epoch);
                expect(converter._calculateTimeSinceEra()).toEqual(false);
            });
        });

        describe('processEra is called each time eraSet is called', () => {
            const date = new Date(1321053060000); // 11/11/11 23:11 UTC
            const converter = new DateConverter(date, false);

            test('it sets the full year', () => {
                expect(converter.year.full).toEqual(2011);
            });

            test('it sets the short year', () => {
                expect(converter.year.short).toEqual("11");
            });

            test('it sets the month in number form', () => {
                expect(converter.month.int).toEqual(11);
            });

            test('it sets the month in string form', () => {
                expect(converter.month.str.full).toEqual('November');
            });

            test('it sets the month in shortened string form', () => {
                expect(converter.month.str.short).toEqual('Nov');
            });

            test('it sets the date', () => {
                expect(converter.date).toEqual(11);
            });

            test('it sets the day in number form', () => {
                expect(converter.day.int).toEqual(6);
            });

            test('it sets the day in string form', () => {
                expect(converter.day.str.full).toEqual('Saturday');
            });

            test('it sets the day in shortened string form', () => {
                expect(converter.day.str.short).toEqual('Sat');
            });

            test('it sets the hours', () => {
                converter.toggle24HourTime();
                expect(converter.hours).toEqual(date.getHours());
            });

            test('it sets the minutes', () => {
                expect(converter.minutes).toEqual(date.getMinutes());
            });
        });

        describe('formatedString', () => {
            const date      = new Date(1321053060000); // 11/11/11 23:11 UTC
            const converter = new DateConverter(date, false);
            let hours  = date.getHours();
            let amOrPm = 'am';
            if(hours > 12) {
                hours  = hours - 12;
                amOrPm = 'pm';
            }

            let minutes = date.getMinutes();
            minutes     = minutes > 0 ? minutes : "00";
            const time = `${hours}:${minutes} ${amOrPm}`;
            it('outputs correctly formated full timestamp', () => {
                expect(converter.formatedString(DateConverter.FULL_TIMESTAMP))
                    .toEqual("Sat, Nov 11, 2011 at " + time);
            });

            it('outputs correctly formated partial timestamp', () => {
                expect(converter.formatedString(DateConverter.PARTIAL_TIMESTAMP))
                    .toEqual(time);
            });

            it('outputs correctly formated relative timestamp', () => {
                //TODO: Needs more setup to demonstrate the relative nature
                // Right now, since 11/11/11 exceeds one years difference,
                // it is shown in DD/MM/YY or MM/DD/YY.
                expect(converter.formatedString(DateConverter.RELATIVE_TIMESTAMP))
                    .toEqual("11/11/11");
            });

            it('outputs correctly formated year month day timestamp', () => {
                expect(converter.formatedString(DateConverter.YMD))
                    .toEqual("11/11/11");
            });

        });
    });
});
