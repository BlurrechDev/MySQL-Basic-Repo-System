part of lunaris;

const int TRIBES = 6;

const int SPAWN_DRONES = 7; //5
const int MAX_DRONES = 160; //80
const int SPAWN_CHANCE = 40; //100
const bool SPAWNERS_VISIBLE = true;

/*
 * Supports multiple tribes with a shared banner.
 */
List<Tribe> getTribesOfBanner(List<Tribe> tribes, int banner) {
  return tribes.where((Tribe tribe) => tribe.banner == banner);
}

/*
 * This may not be needed in the end,
 * but it's easier to remove than to add 
 * later.
 */
Tribe getFirstTribeOfBanner(List<Tribe> tribes, int banner) {
  return getTribesOfBanner(tribes, banner).first;
}

class Station {
  final Player player = new Player();
  final List<HeldTribe> tribes = new List<HeldTribe>();
  
  num initialWidth;
  num initialHeight;
  num scaleWidth = 1;
  num scaleHeight = 1;
  
  List<Drone> get drones {
    List<Drone> allDrones = new List<Drone>();
    for (HeldTribe tribe in tribes) {
      allDrones.addAll(tribe.members);
    }
    return allDrones;
  }
  
  ///Needs a common class for shared methods, or an interface
  List<Drawable> get drawables {
    List<Drawable> drawables = new List<Drawable>();
    drawables.addAll(tribes);
    drawables.add(player);
    //return (tribes.toList() as List<Object>)..add(player);
    return drawables;
  }
  
  resize() {
    if (initialWidth == null || initialHeight == null) {
      initialWidth = canvas.width;
      initialHeight = canvas.height;
    }
    final num prevWidth = scaleWidth;
    final num prevHeight = scaleHeight;
    scaleWidth = canvas.width / initialWidth;
    scaleHeight = canvas.height / initialHeight;
    for (Entity entity in drawables) {
      entity.resize(prevWidth, prevHeight, scaleWidth, scaleHeight);
    }
  }
  
  keyInput(keys) {
    player.input(keys);
  }
  
  start() {
    player.healTo(player.maxHealth);
    player.speed = 1.4;
    player.x = (canvas.width / 2) - (player.width / 2); ///Bang in the centre
    player.y = (canvas.height / 2) - (player.height / 2);
    setupTribes();
  }
  
  setupTribes() {
    for (int x = 0; x < TRIBES; x++) {
      tribes.add(new HeldTribe(tribes)..growHeld(spawnCount: 1));
    }
  }
  
  end() {
    tribes.clear();
  }

  draw() {
    drawables.forEach((Drawable drawable) => drawable.draw());
  }
  
  tick() {
    tribes.forEach((tribe) {
      tribe.tick(drones);
    });
  }

}
