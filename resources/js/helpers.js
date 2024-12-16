window.toLocaleDateTime = (date) => {
    return DateTime.fromISO(date)
        .toLocaleString(DateTime.DATETIME_SHORT)
        .replace(",", "");
};
window.toLocaleTime = (time) => {
    return DateTime.fromISO(time).toLocaleString(DateTime.TIME_SIMPLE);
};
window.toZoneTime = (time, zone) => {
    return DateTime.fromISO(time)
        .setZone(zone)
        .toLocaleString(DateTime.TIME_SIMPLE);
};
