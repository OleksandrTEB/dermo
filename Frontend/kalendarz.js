export class Kalendarz {
    constructor() {
        this.months = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    }

    isLeapYear(year) {
        return (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
    }

    daysInMonth(year, mouth) {
        if (mouth === 2 && this.isLeapYear(year)) {
            return 29;
        } else {
            return this.months[mouth - 1];
        }
    }

    getFirstDayIndex(year, mouth) {
        const firstDay = new Date(year, mouth - 1, 1);
        let day = firstDay.getDay();

        if(day === 0) day = 7;
        return day;
    }
}