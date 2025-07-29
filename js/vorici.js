// vorici-chromatic.js

function fact(n) {
  return n <= 1 ? 1 : n * fact(n - 1)
}

function getDistribution(R, G, B) {
  const stats = [R, G, B]
  const nz    = stats.filter(x => x > 0).length
  const total = R + G + B

  if (nz === 1) {
    const cnt = R || G || B
    const on  = 0.9 * (cnt + 10) / (cnt + 20)
    const off = 0.05 + 4.5 / (cnt + 20)
    return [ R ? on : off, G ? on : off, B ? on : off ]
  }
  else if (nz === 2) {
    return [
      R ? 0.9 * R / total : 0.1,
      G ? 0.9 * G / total : 0.1,
      B ? 0.9 * B / total : 0.1
    ]
  }
  else {
    return [ R / total, G / total, B / total ]
  }
}

function multProb(r, g, b, pR, pG, pB) {
  const total = r + g + b
  const comb  = fact(total) / (fact(r) * fact(g) * fact(b))
  return comb * pR**r * pG**g * pB**b
}

function getChromaticSuccessChance(dR, dG, dB, pR, pG, pB) {
  const total = dR + dG + dB
  let sum = 0

  for (let r = 0; r <= total; r++) {
    for (let g = 0; g <= total - r; g++) {
      const b = total - r - g
      if (r === dR && g === dG && b === dB) {
        sum += multProb(r, g, b, pR, pG, pB)
      }
    }
  }

  return sum
}

const nameMap = {
  'Chromatic Orb': '색채의 오브',
  'Vorici 1R':    '1빨',
  'Vorici 1G':    '1초',
  'Vorici 1B':    '1파',
  'Vorici 1R1G':  '1빨1초',
  'Vorici 1G1B':  '1초1파',
  'Vorici 1R1B':  '1빨1파',
  'Vorici 2G':    '2초',
  'Vorici 2B':    '2파',
  'Vorici 2B1R':  '2파1빨',
  'Vorici 1G2B':  '1초2파',
  'Vorici 2G1R':  '2초1빨',
  'Vorici 3R':    '3빨',
  'Vorici 3G':    '3초',
  'Vorici 3B':    '3파'
}

function calculate() {
  const socketInput = document.getElementById('socket')
  const reqStrInput = document.getElementById('req_str')
  const reqDexInput = document.getElementById('req_dex')
  const reqIntInput = document.getElementById('req_int')
  const desRInput   = document.getElementById('des_r')
  const desGInput   = document.getElementById('des_g')
  const desBInput   = document.getElementById('des_b')
  const resultBody  = document.getElementById('resultTable')
  const lang        = document.documentElement.lang === 'ko' ? 'ko' : 'en'

  const S  = +socketInput.value
  const R  = +reqStrInput.value
  const G  = +reqDexInput.value
  const B  = +reqIntInput.value
  const dR = +desRInput.value
  const dG = +desGInput.value
  const dB = +desBInput.value

  if (dR + dG + dB !== S) {
    alert(
      lang === 'ko'
        ? '원하는 소켓 수 합이 총 소켓 수와 일치해야 합니다'
        : 'Desired sockets must sum to total sockets'
    )
    return
  }

  const [pR, pG, pB] = getDistribution(R, G, B)

  const recipes = [
    ['Chromatic Orb',  1,   0, 0, 0],
    ['Vorici 1R',       4,   1, 0, 0],
    ['Vorici 1G',       4,   0, 1, 0],
    ['Vorici 1B',       4,   0, 0, 1],
    ['Vorici 1R1G',    15,   1, 1, 0],
    ['Vorici 1G1B',    15,   0, 1, 1],
    ['Vorici 1R1B',    15,   1, 0, 1],
    ['Vorici 2G',      25,   0, 2, 0],
    ['Vorici 2B',      25,   0, 0, 2],
    ['Vorici 2B1R',   100,   1, 0, 2],
    ['Vorici 1G2B',   100,   0, 1, 2],
    ['Vorici 2G1R',   100,   1, 2, 0],
    ['Vorici 3R',     120,   3, 0, 0],
    ['Vorici 3G',     120,   0, 3, 0],
    ['Vorici 3B',     120,   0, 0, 3]
  ]

  const results = recipes.map(([name, cost, rFix, gFix, bFix]) => {
    const rLeft = dR - rFix
    const gLeft = dG - gFix
    const bLeft = dB - bFix
    const free  = S - (rFix + gFix + bFix)

    if (rLeft < 0 || gLeft < 0 || bLeft < 0 || rLeft + gLeft + bLeft !== free) {
      return null
    }

    const success = name === 'Chromatic Orb'
      ? getChromaticSuccessChance(dR, dG, dB, pR, pG, pB)
      : multProb(rLeft, gLeft, bLeft, pR, pG, pB)

    return {
      name,
      avgCost: cost / success,
      success,
      attempts: 1 / success
    }
  }).filter(x => x)

  const minCost = Math.min(...results.map(x => x.avgCost))
  resultBody.innerHTML = ''

  results.forEach(rw => {
    const isBest   = rw.avgCost === minCost
    const display  = lang === 'ko' ? (nameMap[rw.name] || rw.name) : rw.name
    const costCell = Math.round(rw.avgCost).toLocaleString()
    const succPct  = (rw.success * 100).toFixed(5) + '%'
    const atts     = rw.attempts.toFixed(1)

    resultBody.insertAdjacentHTML('beforeend',
      `<tr${isBest ? ' class="highlight"' : ''}>` +
        `<td>${display}</td>` +
        `<td${isBest ? ' class="cost-cell cross-cell"' : ''}>${costCell}</td>` +
        `<td>${succPct}</td>` +
        `<td>${atts}</td>` +
      `</tr>`
    )
  })
}

