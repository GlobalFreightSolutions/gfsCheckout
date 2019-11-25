import toDate from"../toDate/index.js";import startOfWeek from"../startOfWeek/index.js";import addWeeks from"../addWeeks/index.js";export default function eachWeekOfInterval(dirtyInterval,options){if(1>arguments.length){throw new TypeError("1 argument required, but only "+arguments.length+" present")}var interval=dirtyInterval||{},startDate=toDate(interval.start),endDate=toDate(interval.end),endTime=endDate.getTime();if(!(startDate.getTime()<=endTime)){throw new RangeError("Invalid interval")}var startDateWeek=startOfWeek(startDate,options),endDateWeek=startOfWeek(endDate,options);startDateWeek.setHours(15);endDateWeek.setHours(15);endTime=endDateWeek.getTime();var weeks=[],currentWeek=startDateWeek;while(currentWeek.getTime()<=endTime){currentWeek.setHours(0);weeks.push(toDate(currentWeek));currentWeek=addWeeks(currentWeek,1);currentWeek.setHours(15)}return weeks}