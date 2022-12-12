export const isSmallScreen = size => {
  return window.matchMedia(`(max-width: ${size})`).matches;
};
