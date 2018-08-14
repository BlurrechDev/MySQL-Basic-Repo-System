part of lunaris;

class Player extends SuperTribal with Controllable {
  int lives = 3;
  int shieldPower = -50;
  
  Player() : super(NEUTRAL, 'player.gif') {
    x = -width;
    y = -height;
  }
  
  bool get shieldCharged => shieldPower > 0;
  bool get shieldCd => shieldPower < -50; ///Cdc - Cooldown Complete or CooledDown
  num get radius => width * (shieldPower / 20) + width / 5;
  
  attack(List<Drone> drones, {List<DroneSpawner> spawners}) {
    super.attack(drones);
    if (!shieldCharged) return;
  }
  
  upgradeSpeed(List<Drone> drones) {
    for (PlayerDrone drone in drones.where((element) => element is PlayerDrone)) drone.speed = speed;
  }
  
  draw() {
    super.draw();
    drawShieldPowerBar();
    drawShield();
  }
  
  drawShield() {
    if (radius > 0) {
      context.strokeStyle = 'white';
      context.beginPath();
      context.arc(x + width / 2, y + height / 2, radius, 0, 2*PI);
      context.stroke();
    }
    shieldPower--;
  }
  
  drawShieldPowerBar() {
    context.fillStyle = 'grey';
    context.fillRect(x, y - 30, width, 10);
    context.fillStyle = 'orange';
    if (shieldPower < 0) {
      num cooldown = shieldPower.abs() / 50;
      if (cooldown > 1) cooldown = 1;
      context.fillRect(x, y - 30, cooldown * width, 10);
    }
  }
  
  input(keys) {
    super.input(keys);
    if (keys[SPACE] && shieldCd) {
      shieldPower = 40;
    }
  }
}