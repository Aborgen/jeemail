// Taken from https://stackoverflow.com/a/49857905
// As of June 2018, JS's native fetch api does not implement a timeout.
// This function will return a Promise.race, which will either resolve or be
// rejected, reflecting the first promise that resolves or is rejected.
// If the fetch fails to either resolve or reject, the anonymous Promise will
// reject after timeout.

export default function (url, options, timeout = 10000) {
    return Promise.race([
        fetch(url, options),
        new Promise((_, reject) =>
            setTimeout(() => reject(new Error('timeout')), timeout)
        )
    ]);
}
