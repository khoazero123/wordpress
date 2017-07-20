
Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

var Elevator = {};
Elevator.floors = [1,2,3,4,5,6,7];
//Elevator.elements = [1,2];
Elevator.elements = {1:1,2:1}; // id: position
Elevator.total = Object.size(Elevator.elements);
Elevator.free = Object.keys(Elevator.elements);

Elevator.busy = [];
Elevator.floorWaiting = [];
Elevator.init = function () {

};
// calling, used, 
/**
 * excute when press button call elevator
 * @param int currentFloor - floor call elevator
 * @param boolean goto (0 - down, 1 - up)
 */
Elevator.call = function(currentFloor,goto) { // Elevator.call(2,0)
    // check which elevator is free, if none add currentFloor to Elevator.floorWaiting
    console.log('call elavator from floor '+currentFloor+', go '+goto);
    var useElavator;
    if (Elevator.free.length > 0) {
        // check position elevator
        useElavator = Elevator.selectElement(Elevator.free,currentFloor);
        console.log('useElavator: '+useElavator);
        Elevator.busy.push(useElavator);
        //Elevator.free.splice(Elevator.free.indexOf(useElavator), 1);
        Elevator.free.remove(useElavator);
    } else {
        Elevator.floorWaiting.push(currentFloor);
        console.log('All elevator are busy, add floor '+currentFloor+' to list waiting');
    }
}
Elevator.getPosition = function() {

}
Elevator.arrivedPosition = function(element,floor) {
    Elevator.elements.element = floor;
}
Elevator.selectElement = function(elements,floor) {
    elements.forEach(function(entry) {
        //var index = Elevator.elements.indexOf(entry);
        //var floorOfElement = Elevator.elements[index];
        //if(floorOfElement > floor)
    });
    return elements[0];
}


/*

_________________________________
T7
_________________|________________
T6
_________________|________________
T5   call
_________________|________________
T4
_________________|________________
T3
_________________|________________
T2
_________________|________________
T1    |   |             |   |
_________________|________________



*/
