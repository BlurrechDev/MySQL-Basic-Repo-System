library lunaris;

import 'dart:html';
import 'dart:math' show Random, PI;
import '../../empirical.dart';

import '../entity/entity.dart';

part 'game/controllable.dart';
part 'game/station.dart';
part 'game/player.dart';
part 'game/tribe.dart';
part 'game/held_tribe.dart';

part 'game/drone/drone.dart';
part 'game/drone/tribe_drone.dart';
part 'game/drone/player_drone.dart';

part 'game/drone/tribe/super_tribal.dart';
part 'game/drone/tribe/gravity_tribal.dart';
part 'game/drone/tribe/target_tribal.dart';
part 'game/drone/tribe/vampire_tribal.dart';
part 'game/drone/tribe/regen_tribal.dart';

part 'game/drone/spawner/drone_spawner.dart';
part 'game/drone/spawner/patrol_spawner.dart';
part 'game/drone/spawner/tribe_spawner.dart';

main() => new Lunaris();

const CUSTOM_MAIN = const MainMenu('''
<h1> Lunraris </h1>
<a id="startbutton"> Start </a>
<a id="optionsbutton"> Options </a>
<a id="exitbutton"> Exit </a>
''');

const UP = 38;
const LEFT = 37;
const DOWN = 40;
const RIGHT = 39;
const SPACE = 32;

const W = 87;
const A = 65;
const S = 83;
const D = 68;

final Random random = new Random();

class Lunaris extends Empirical {
  final station = new Station();
  
  @override
  loadInterface() {
    loadMenu(CUSTOM_MAIN);
    super.loadInterface();
  }
  
  @override
  start() {
    station.start();
  }
  
  @override
  pause() {
    
  }
  
  @override
  end() {
    station.end();
  }
  
  @override
  tick() {
    station.keyInput(keys);
    station.tick();
  }
  
  @override
  draw() {
    station.draw();
  }
  
  @override
  resize() {
    station.resize();
  }
  
  @override
  click(num x, num y) {
     
  }
  
}
