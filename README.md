# ASX Stock Float Scanner

A tool made with Laravel that stores and keeps up-to-date a list of ASX-listed companies along with the amount of shares that company has released to the public (float).

This metric of float is useful for intraday equity trading as a trader can measure the likelihood of a stock rapidly increasing in price by looking at the float compared to an average volume for that stock. 

## Setup

Use [Lando](https://github.com/lando/hyperdrive) for local dev.

```sh
lando start
```

## Data Sources

* Yahoo Finance
* ASX 