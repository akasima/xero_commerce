module.exports = function (css) {
  // Here we can change the original css
  const transformed = css.replace(/.navbar/g, '.navbar-unuse')
      .replace(/.navbar > .container/g, '.navbar > .container-unuse')
      .replace(/body/g,'body-unuse')
      .replace(/.container/g, '.container-unuse')
      .replace(/::after/g,'::notAfter')

  return transformed
}