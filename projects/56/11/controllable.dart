part of lunaris;

abstract class Controllable {
  num x, y, speed;
  
  input(keys) {
    if (keys[W] || keys[UP]) {
      y -= speed;
    }
    if (keys[A] || keys[LEFT]) {
      x -= speed;
    }
    if (keys[S] || keys[DOWN]) {
      y += speed;
    }
    if (keys[D] || keys[RIGHT]) {
      x += speed;
    }
  }
  
}