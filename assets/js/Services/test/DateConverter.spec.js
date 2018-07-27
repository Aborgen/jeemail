import DateConverter from '../DateConverter.js';

describe('it throws' , () => {
    test('an Error if no timestamp is provided in the constructor', () => {
        expect(() => new DateConverter()).toThrow(Error);
    });

    test('a TypeError if timestamp is not a number', () => {
        expect(() => new DateConverter(NaN)).toThrow(TypeError);
        expect(() => new DateConverter('not a number')).toThrow(TypeError);
        expect(() => new DateConverter('25')).toThrow(TypeError);
        expect(() => new DateConverter({})).toThrow(TypeError);
        expect(() => new DateConverter([])).toThrow(TypeError);
        expect(() => new DateConverter([25])).toThrow(TypeError);
    });

    test('a RangeError if timestamp is not in milliseconds', () => {
        expect(() => new DateConverter(999)).toThrow(RangeError);
    });
});

describe('in state, it', () => {
    const now          = new Date();
    const is24HourTime = Math.random() < 0.5;
    const converter    = new DateConverter(now, is24HourTime);

    test('sets Date object from provided Date object', () => {
        expect(converter.getDate()).toBeInstanceOf(Date);
    });

    test('sets Date object from provided timestamp', () => {
        const nowMs      = now.getTime();
        const converter2 = new DateConverter(nowMs);
        expect(converter2.getDate()).toBeInstanceOf(Date);
    });

    test('sets locale from the client\'s browser', () => {
        // If navigator properties language and languages are undefined, fall
        // back to 'en' string. This is a compressed version of how
        // DateConverter does it, by the way.
        const locale = navigator.language || (navigator.languages || ["en"])[0];
        expect(converter.getLocale()).toEqual(locale);
    });

    test('determines whether or not the client uses 24-hour time ' +
         'based on the second argument in the constructor' , () => {
            expect(converter.is24HourTime).toEqual(is24HourTime);
    });
});

describe('the "private" methods:', () => {
    const now = new Date();
    describe('_msToDate', () => {
        const converter = new DateConverter(now);

        test('safely converts an integer >= 1000 into a Date object', () => {
            expect(converter._msToDate(1000)).toBeInstanceOf(Date);
        });

        test('throws a RangeError if provided an int less than 1000', () => {
            expect(() => converter._msToDate(999)).toThrow(RangeError);
        });

        test('throws a TypeError if not provided a number', () => {
            expect(() => converter._msToDate(NaN)).toThrow(TypeError);
            expect(() => converter._msToDate('not a number')).toThrow(TypeError);
            expect(() => converter._msToDate('25')).toThrow(TypeError);
            expect(() => converter._msToDate({})).toThrow(TypeError);
            expect(() => converter._msToDate([])).toThrow(TypeError);
            expect(() => converter._msToDate([25])).toThrow(TypeError);
        });
    });

    describe('_calculateTimeSinceDate', () => {
        const nowMs = now.getTime();
        const converter = new DateConverter(nowMs);

        test('return 0 if time elapsed since provided timestamp ' +
             'is less than 30 seconds', () => {
            expect(converter._calculateTimeSinceDate()).toEqual(0)
        });

        test('return { seconds } if time elapsed is less than ' +
             '60 seconds', () => {
            const futureDate = nowMs - (35 * 1000);
            converter.setDate(futureDate);
            expect(converter._calculateTimeSinceDate()).toEqual(
                expect.objectContaining({ seconds: 35 })
            );
        });

        test('return { minutes } if time elapsed is less than ' +
             '60 minutes', () => {
            const futureDate = nowMs - (35 * 60000);
            converter.setDate(futureDate);
            expect(converter._calculateTimeSinceDate()).toEqual(
                expect.objectContaining({ minutes: 35 })
            );
        });

        test('return { hours } if time elapsed is less than ' +
             '24 hours', () => {
            const futureDate = nowMs - (4 * 3600000);
            converter.setDate(futureDate);
            expect(converter._calculateTimeSinceDate()).toEqual(
                expect.objectContaining({ hours: 4 })
            );
        });

        test('return { hours, minutes } if time elapsed is ' +
             'more than 7 hours', () => {
            const futureDate = nowMs - (10 * 3600000);
            converter.setDate(futureDate);
            expect(converter._calculateTimeSinceDate()).toEqual(
                expect.objectContaining({ hours: 10, minutes: 0 })
            );
        });

        test('return { days } if time elapsed is less than 30 days', () => {
            const futureDate = nowMs - (10 * 86400000);
            converter.setDate(futureDate);
            expect(converter._calculateTimeSinceDate()).toEqual(
                expect.objectContaining({ days: 10 })
            );
        });

        test('otherwise, return false', () => {
            const epoch = new Date(0);
            converter.setDate(epoch);
            expect(converter._calculateTimeSinceDate()).toEqual(false);
        });
    });
});
