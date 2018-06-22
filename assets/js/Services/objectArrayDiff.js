function objectArrayDiff(prevArray, newArray) {
    const prevLength = prevArray.length || 0;
    const newLength  = newArray.length  || 0;
    if(prevLength !== newLength || prevLength + newLength === 0) {
        return true;
    }
    else if(prevArray[0].id === newArray[0].id &&
            prevArray[prevLength - 1].id === newArray[newLength - 1].id) {
        return false;
    }
    else {
        return true;
    }
}

export default objectArrayDiff;
