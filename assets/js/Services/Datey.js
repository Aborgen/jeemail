function Datey(ms) {
    const now = new Date();

    const inputOffset   = getTimezoneOffset(ms)
    const currentOffset = getTimezoneOffset(now);
    const deltaOffset   = (inputOffset - currentOffset) * 1000 * 60 * 60;
    const nowTimestamp  = now.getTime();

    return msToDate(deltaOffsetMs + nowTimeStamp);
}

function getTimestampOffset(dateObject) {
    if(!(dateObject instanceof Date)) {
        dateObject = msToDate(dateObject);
    }

    return -dateObject.getTimezoneOffset() / 60;
}

function msToDate(ms) {
    if(isNaN(ms)) {
        throw new TypeError("msToDate only accepts numbers");
    }

    if(ms / 1000 < 1) {
        throw new RangeError("msToDate requires that ms be in milliseconds");
    }

    return new Date(ms);
}
