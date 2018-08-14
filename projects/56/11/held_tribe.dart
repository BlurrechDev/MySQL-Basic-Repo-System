part of lunaris;

class HeldTribe extends Tribe implements Drawable {
  final List<TribeDrone> members = new List<TribeDrone>();
  TribeSpawner _spawner;
  int banner;
  int hostility = PEACEFUL;
  int leadership = GOVERNED;
  bool hasCultured = false;
  
  Rectangle territory;
  
  HeldTribe(List<HeldTribe> tribes) : super(tribes) {
    territory = getRealTerritory(tribes);
    banner = Banner.getFreeBanner();
    _spawner = new TribeSpawner(getCenterPoint().x, getCenterPoint().y, banner);
  }
  
  tick(List<LivingEntity> drones) {
    members.removeWhere((TribeDrone member) {
      member.tick(drones);
      return member.dead;
    });
    if (drones.length < (MAX_DRONES - SPAWN_DRONES) && random.nextInt(SPAWN_CHANCE) == 0) {
      growHeld(spawnCount: SPAWN_DRONES);
    }
    if (hasCultured) {
      if (isHeldExtinct()) {
        leadership = EXTINCT;
      } else {
        if (isVictorious(drones)) {
          leadership = VICTORS;
        }
      }
    } else {
      hasCultured = true;
    }
  }
  
  growHeld({num spawnCount: 1}) {
    switch (leadership) {
      case EXTINCT:
        break;
      case VICTORS:
        members.clear();
        break;
      case GOVERNED:
      default:
        for (int x = 0; x <= spawnCount; x++) _spawner.spawnHere(members);
        break;
    }
  }
  
  List<LivingEntity> getOthers(List<LivingEntity> drones) {
    return drones.where((LivingEntity entity) {
      return entity is TribeDrone && entity.banner != banner;
    });
  }
  
  bool isHeldExtinct() {
    return members.length <= 0;
  }
  
  bool isVictorious(List<LivingEntity> drones) {
    return false;
    //return getOthers(drones).length <= 0;
  }
  
  stretch(num scaleWidth, num scaleHeight) {
    members.forEach((TribeDrone member) => member.stretch(scaleWidth, scaleHeight));
  }
  
  shrink(num prevWidth, num prevHeight) {
    members.forEach((TribeDrone member) => member.shrink(prevWidth, prevHeight));
  }
  
  resize(num prevWidth, num prevHeight, num scaleWidth, num scaleHeight) {
    shrink(prevWidth, prevHeight);
    stretch(scaleWidth, scaleHeight);
  }
  
  draw() {
    members.forEach((TribeDrone member) => member.draw());
    switch (leadership) {
      case EXTINCT:
        context.fillStyle = Banner.bannerToColor(banner);
        context.fillRect(territory.topLeft.x, territory.topLeft.y, territory.width, territory.height);
        break;
      default:
        context.strokeStyle = Banner.bannerToColor(banner);
        context.strokeRect(territory.topLeft.x, territory.topLeft.y, territory.width, territory.height);
    }
  }
  
  Point getCenterPoint() {
    return new Point(territory.topLeft.x + (territory.width / 2), territory.topLeft.y + (territory.height / 2));
  }
  
  bool territoryIntrusive(List<HeldTribe> tribes, Rectangle newTerritory) {
    for (Tribe tribe in tribes) {
      if (newTerritory.intersects(tribe.territory)) {
        return true;
      }
    }
    return false;
  }
  
  Rectangle getRealTerritory(List<HeldTribe> tribes) {
    num width = dynX(TERRITORY_PERCENT); //5%;
    num height = dynY(TERRITORY_PERCENT); //5%;
    
    num x = dynX(getCoordForTerritory());
    num y = dynY(getCoordForTerritory());
    
    Rectangle newTerritory = new Rectangle(x, y, width, height);
    if (territoryIntrusive(tribes, newTerritory)) {
      return getRealTerritory(tribes);
    } else {
      return newTerritory;
    }
  }
  
  num getCoordForTerritory() {
    return TERRITORY_PERCENT * random.nextInt((1 / TERRITORY_PERCENT).round());
  }
  
  bool canAttack(HeldTribe ofTarget) {
    switch (hostility) {
      case PEACEFUL:
        return false;
      default:
        return false;
    }
  }
  
  
}