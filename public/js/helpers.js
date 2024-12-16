function formatDateTime(date) {
    return DateTime.fromISO(date).toLocaleString(DateTime.DATETIME_SHORT);
}
