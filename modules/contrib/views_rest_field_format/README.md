# Drupal Views REST Field Format

## Contents of this file

- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Maintainers](#maintainers)

## Introduction

Implements a custom `ViewsRow` plugin that allows to specifically define
the data format by field for serialization.


This allows that e.g. a number from an ID is actually a number in JSON and
not a string.

### Usage

Just select `Formatted Fields` in a REST export views and select the desired
data format for each field in settings.

## Requirements

The core module requires Drupal 10 or 11.

## Installation

Install as you would normally install a contributed Drupal module. For further
information, see
[Installing Drupal Modules](https://www.drupal.org/docs/extending-drupal/installing-drupal-modules).

## Configuration

The module itself does not support configuration.

## Maintainers

Current maintainers:

- Christoph Niedermoser ([@nimoatwoodway](https://www.drupal.org/u/nimoatwoodway))
- Christian Foidl ([@chfoidl](https://www.drupal.org/u/chfoidl))
