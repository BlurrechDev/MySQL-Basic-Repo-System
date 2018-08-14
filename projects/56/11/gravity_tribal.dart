part of lunaris;

class GravityTribal extends SuperTribal {
  
  GravityTribal(int banner) : super(banner, 'gravity_drone.gif');
  
  bool get gravityPulling => shieldCharged && health > 10;
  
  @override
  drawShield() {
    if (gravityPulling) {
      context.strokeStyle = 'blue';
    } else {
      context.strokeStyle = 'red';
    }
    context.beginPath();
    context.arc(x + width / 2, y + height / 2, radius * 1.5, 0, 2*PI);
    context.stroke();
    
    context.strokeStyle = 'white';
    context.beginPath();
    context.arc(x + width / 2, y + height / 2, radius, 0, 2*PI);
    context.stroke(); 
  }
  
  @override
  attack(List<Drone> drones) {
    super.attack(drones);
    if (gravityPulling) {
      gravityPull(drones);
    } else {
      gravityPush(drones);
    }
  }
  
  /*
   * Returns whether any entities in the list where pushed. 
   */
  bool gravityPush(List<Entity> drones) {
    drones.where((Entity entity) => inProximity(entity, radius * 1.5) && canAttack(entity))
      .forEach((Drone entity) {
      if (entity.x < x) {
        entity.moveX(-speed);
      } else {
        entity.moveX(speed);
      }
      
      if (entity.y < y) {
        entity.moveY(-speed);
      } else {
        entity.moveY(speed);
      }
      return true;
    });
    return false;
  }

  /*
   * Returns whether any entities in the list where pulled. 
   */
  gravityPull(List<Entity> entities) {
    entities.where((Entity entity) => inProximity(entity, radius * 1.5) && canAttack(entity))
      .forEach((Drone entity) {
      if (entity.x < x) {
        entity.moveX(speed);
      } else {
        entity.moveX(-speed);
      }
      
      if (entity.y < y) {
        entity.moveY(speed);
      } else {
        entity.moveY(-speed);
      }
      return true;
    });
    return false;
  }
  
}