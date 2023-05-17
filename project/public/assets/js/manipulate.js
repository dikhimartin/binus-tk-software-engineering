function LimitTextByDot(text, maxLength) {
    const dotIndex = text.indexOf('.');
    if (dotIndex !== -1 && dotIndex < maxLength) {
      return text.slice(0, dotIndex + 1);
    }
    return text.slice(0, maxLength);
  }
  