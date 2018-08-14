part of lunaris;

class TribeDrone extends Drone {
  final int banner;
  
  TribeDrone(this.banner, {String sprite: 'round_drone.gif'}) : super(sprite: sprite) {
    healthColor = Banner.bannerToColor(banner);
  }
  
  @override
  bool canAttack(LivingEntity entity) {
    if (entity is TribeDrone && entity.banner == banner) {
      return false;
    } else {
      return true;
    }
  }
  
}