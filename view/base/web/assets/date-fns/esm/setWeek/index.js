import toInteger from"../_lib/toInteger/index.js";import toDate from"../toDate/index.js";import getWeek from"../getWeek/index.js";export default function setWeek(dirtyDate,dirtyWeek,dirtyOptions){if(2>arguments.length){throw new TypeError("2 arguments required, but only "+arguments.length+" present")}var date=toDate(dirtyDate),week=toInteger(dirtyWeek),diff=getWeek(date,dirtyOptions)-week;date.setDate(date.getDate()-7*diff);return date}