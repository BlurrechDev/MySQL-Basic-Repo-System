part of lunaris;

const bool DRAW_EFFECTS = true;
const bool DRAW_HEALTHBARS = true && DRAW_EFFECTS;
const bool DRAW_SHIELDS = true && DRAW_EFFECTS;

class Drone extends LivingEntity {
  bool shieldCharged = false;
  String healthColor = \'green\';
  
  Drone({String sprite: \'round_drone.gif\'}) : super(sprite) {
    randomCoordinates();
  }
  
  num get radius => width * 1.5;
  
  bool canAttack(LivingEntity entity) => true;
  
  tick(List drones) {
    move();
    shieldAttack(drones);
  }
  
  shieldAttack(List drones) {
    if (!shieldCharged) return;
    for (Drone targetDrone in drones.toList()) {
      if (!canAttack(targetDrone)) continue;
      if (this is PlayerDrone && targetDrone is PlayerDrone) continue;
      if (inProximity(targetDrone, radius)) targetDrone.damage(1, this);
    }
  }
  
  @override
  death({LivingEntity murderr}) {
    super.death(murderr: murderr);
    if (murderer is Drone) (murderer as Drone).engorge(this);
  }
  
  randomCoordinates() {
    x = random.nextInt(canvas.width.toInt());
    y = random.nextInt(canvas.height.toInt());
  }
  
  engorge(LivingEntity defeated) {
    vitalize(20);
    healBy(20);
    if (width > 60) return;
    width += defeated.width / 15;
    height += defeated.height / 15;
    speed += defeated.speed / 7;
  }
  
  draw() {
    super.draw();
    if (DRAW_HEALTHBARS) drawHealthBar();
    if (DRAW_SHIELDS) drawShield();
  }
  
  drawHealthBar() {
    context.fillStyle = \'red\';
    context.fillRect(x, (y - 20), width, 10);
    context.fillStyle = healthColor;
    context.fillRect(x, (y - 20), (health/maxHealth) * width, 10);
  }
  
  drawShield() {
    if (shieldCharged) {
      context.strokeStyle = \'white\';
      context.beginPath();
      context.arc(x + width / 2, y + height / 2, radius, 0, 2*PI);
      context.stroke();
    }
  }
  
  move() {
    switch(random.nextInt(5)) {
      case 0:
        moveY(-speed);
        break;
      case 1:
        moveX(-speed);
        break;
      case 2:
        moveY(speed);
        break;
      case 3:
        moveX(speed);
        break;
      case 4:
        shieldCharge();
        break;
    }
  }
  
  // Generic code change comment.
  
  shieldCharge() {
    if (random.nextInt(100) == 0) shieldCharged = !shieldCharged;
  }
}
