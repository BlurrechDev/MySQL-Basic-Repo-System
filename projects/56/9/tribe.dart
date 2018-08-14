part of lunaris;

const TERRITORY_PERCENT = 0.10;

/*
 * Aggression Mindset - Hostility
 */
const int PEACEFUL = 59; //No violence, defenceless but resourceful
const int DEFENSIVE = 60; //Defends the tribes territory
const int TERRITORIAL = 61; //Attacks others in territory and defends
const int WARRING = 62; //Violent, however only engages with one tribe at a time
const int VENGEFUl = 63; //Warring, seeks conflict and aims to fight any other tribe


/*
 * Leadership Mindset
 */
const int GOVERNED = 64;
const int ANARCHY = 65; //Leaderless, barely a tribe
const int ENSLAVED = 66; //Enslaved by another tribe to gather resources for them
const int EXILED = 67; //A tribe whom have been removed from their territory
const int EXTINCT = 68; //Defeated, no longer able to cultivate as they lack resources, land and leadership
const int VICTORS = 69; //All other tribes dead.

class Tribe {
  TribeSpawner _spawner;
  int banner;
  int hostility = PEACEFUL;
  int leadership = GOVERNED;
  bool hasCultured = false;
  
  Rectangle territory;
  
  Tribe(List<Tribe> tribes) {
    territory = getRealTerritory(tribes);
    banner = Banner.getFreeBanner();
    _spawner = new TribeSpawner(getCenterPoint().x, getCenterPoint().y, banner);
  }
  
  tick(List<LivingEntity> drones) {
    if (hasCultured) {
      if (isExtinct(drones)) {
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
  
  grow(List<LivingEntity> drones, {num spawnCount: 1}) {
    switch (leadership) {
      case EXTINCT:
        break;
      case VICTORS:
        drones.clear();
        break;
      case GOVERNED:
      default:
        for (int x = 0; x <= spawnCount; x++) _spawner.spawnHere(drones);
        break;
    }
  }
  
  List<LivingEntity> getTribeMembers(List<LivingEntity> drones) {
    return drones.where((LivingEntity entity) {
      return entity is TribeDrone && entity.banner == banner;
    });
  }
  
  List<LivingEntity> getOthers(List<LivingEntity> drones) {
    return drones.where((LivingEntity entity) {
      return entity is TribeDrone && entity.banner != banner;
    });
  }
  
  bool isExtinct(List<LivingEntity> drones) {
    return getTribeMembers(drones).length <= 0;
  }
  
  bool isVictorious(List<LivingEntity> drones) {
    return getOthers(drones).length <= 0;
  }
  
  draw() {
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
  
  bool territoryIntrusive(List<Tribe> tribes, Rectangle newTerritory) {
    for (Tribe tribe in tribes) {
      if (newTerritory.intersects(tribe.territory)) {
        return true;
      }
    }
    return false;
  }
  
  Rectangle getRealTerritory(tribes) {
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
  
  bool canAttack(Tribe ofTarget) {
    switch (hostility) {
      case PEACEFUL:
        return false;
      default:
        return false;
    }
  }
  
}

/*
 * Banner types, for differentiation and customization
 */
const int BLUE = 54;
const int VIOLET = 55;
const int GREEN = 56;
const int ORANGE = 57;
const int YELLOW = 58;
const int NEUTRAL = 59;

class Banner {
  static List<int> tribeBanners = [BLUE, VIOLET, GREEN, ORANGE, YELLOW, NEUTRAL];
  
  static resetBanners() {
    tribeBanners = [BLUE, VIOLET, GREEN, ORANGE, YELLOW, NEUTRAL];
  }
  
  static String bannerToColor(int banner) {
    switch (banner) {
      case BLUE:
        return 'blue';
      case VIOLET:
        return 'violet';
      case YELLOW:
        return 'yellow';
      case ORANGE:
        return 'orange';
      case NEUTRAL:
        return 'cyan';
      case GREEN:
      default:
        return 'green';
    }
  }
  
  static int getFreeBanner() {
    if (tribeBanners.length <= 0) resetBanners();
    return Banner.tribeBanners.removeAt(0);
  }
  
}