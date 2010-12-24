<?php
interface IBinding{}
interface ISerialization{}
interface IFeeder{}
interface ISession{}

interface InstanceBound extends IBinding{}
interface StaticBound extends IBinding{}

interface SerializableXDO extends ISerialization{}
interface MemoryXDO extends ISerialization{}

interface ControllerFeeded extends IFeeder{}
interface SelfFeeded extends IFeeder{}
interface SpiritFeeded extends IFeeder{}

interface SessionedSpirit extends ISession{}
interface FloatingSpirit extends ISession{}
?>