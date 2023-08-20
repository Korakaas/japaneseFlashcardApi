function srsFunc(previous, evaluation) {
    var n, efactor, interval

    if (previous == null) {
        previous = {n:0, efactor:2.5, interval:0.0}
    }

    if (previous.n < 3) {
        // Still in learning phase, so do not change efactor
        efactor = previous.efactor

        if (evaluation.score < 3) {
            // Failed, so force re-review in 30 minutes and reset n count
            n = 0
            interval = 30 * 1.0/(24.0*60.0)
        } else {
            n = previous.n + 1

            // first interval = 30min
            // second interval = 12h
            // third interval = 24h
            if (n == 1) {
                // in 30m
                interval = 30.0 * 1.0/(24.0*60.0)
            } else if (n == 2) {
                // in 12h
                interval = 0.5
            } else {
                // in 1d
                interval = 1.0
            }
        }
        // Add 10% "fuzz" to interval to avoid bunching up reviews
        interval = interval * (1.0 + Math.random() * 0.10)
    } else {
        // Reviewing phase

        if (evaluation.score < 3) {
            // Failed, so force re-review in 30 minutes and reset n count
            n = 0
            interval = 30 * 1.0/(24.0*60.0)

            // Reduce efactor
            efactor = Math.max(1.3, previous.efactor - 0.20)
        } else {
            // Passed, so adjust efactor and compute interval


            // First see if this was done close to on time or late. We handle early reviews differently
            // because Fresh Cards allows you to review cards as many times as you'd like, outside of
            // the SRS schedule. See details below in the "early" section.

                // Review was not too early, so handle normally

                n = previous.n + 1

                var intervalAdjustment = 1.0

                // If this review was done late and user still got it right, give a slight bonus to the score of up to 1.0.
                // This means if a card was hard to remember AND it was late, the efactor should be unchanged. On the other
                // hand, if the card was easy, we should bump up the efactor by even more than normal.

                    // Card wasn't late, so adjust differently

                    if (evaluation.score >= 3.0 && evaluation.score < 4) {
                        // hard card, so adjust interval slightly
                        intervalAdjustment = 0.8
                    }


                let adjustedScore =  evaluation.score
                efactor = Math.max(1.3, previous.efactor + (0.1 - (5 - adjustedScore) * (0.08+(5 - adjustedScore)*0.02)))

                // Figure out interval. First review is in 1d, then 6d, then based on efactor and previous interval.
                if (previous.n == 0) {
                    interval = 1
                } else if (previous.n == 1) {
                    interval = 6
                } else {
                    interval = Math.ceil(previous.interval * intervalAdjustment * efactor)
                }


            // Add 5% "fuzz" to interval to avoid bunching up reviews
            interval = interval * (1.0 + Math.random() * 0.05)
        }
    }
    return {n, efactor, interval}
}