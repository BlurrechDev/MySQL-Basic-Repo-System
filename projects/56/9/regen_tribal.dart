part of lunaris;

class RegenTribal extends SuperTribal {

  RegenTribal(int banner) : super(banner, 'regen_drone.gif');
  
  @override
  attack(List<Drone> drones) {
    super.attack(drones);
    if (shieldCharged) {
      healBy(1);
      List<Drone> toHeal = drones.where((Drone drone) {
        return (inProximity(drone, radius) && drone is TribeDrone && drone.banner == banner); 
      }).toList();
      toHeal.forEach((Drone drone) => drone.healBy(1));
    }
  }
  
}