<?php return array (
  'manifest-version' => '1.1',
  'manifest-attributes' => 
  array (
    'changelog' => 'Changelog for YandexMarket2.

1.4.5-beta (13.02.2023)
==============
- При выборе категорий теперь учитываются товары из мультикатегорий ms2
- Добавлено поле msCategory.id для ограничения выборки товара, включая мультикатегории

1.4.4-beta (06.11.2022)
==============
- Исправлена ошибка удаления поля

1.4.3-beta (21.10.2022)
==============
- Исправлена ошибка при сохранении товара

1.4.2-beta (01.10.2022)
==============
- Поправлен формат даты атрибута date элемента yml_catalog согласно новым требованиям

1.4.1-beta (12.09.2022)
==============
- Все тексты вынесены в лексиконы
- Полная локализация под английский язык

1.4.0-beta (10.09.2022)
==============
- Единая версия компонента сразу под MODX 2.x и MODX 3

~~2.0.0-beta (28.08.2022)~~
==============
- MODX 3 версия

1.3.3-beta (27.08.2022)
==============
- поправлена работа под PHP 8.x

1.3.2-beta (01.08.2022)
==============
- теперь файл генерируется с временным названием и переименуется после завершения (улучшения для роботов)
- поправлен формат Google RSS 2.0 (цена и наличие). Для FB наличие нужно править на "in stock"
- выведены настройки для путей файлов, адреса сайта (чтобы и при cron было одинаково)
- исправлена работа под MySQL 8

1.3.1-beta (12.04.2022)
==============
- исправлены ошибки datetime для MariaDB

1.3.0-beta (25.10.2021)
==============
- интеграция с msOptionsPrice2 для выгрузки модификаций
- исправление багов и ошибок
- серверная пагинация и сортировка прайс-листов

1.2.0-beta (26.06.2021)
==============
- добавлен провайдер Google RSS 2.0 для выгрузки в Google Merchant(покупки) и Facebook
- добавлены 3 типа для полей (сырой XML, прозрачные обёртки для предложений и категорий)
- теперь обработка кода возможна и без наличия pdoTools через MODX синтаксис

1.1.1-beta (29.05.2021)
==============
- теперь в качестве товара можно выгружать любой xPDOObject
- поправлен баг с полем picture

1.1.0-beta (22.05.2021)
==============
- множество улучшений и исправлений ошибок и недочётов
- добавлены системные настройки debug_mode, prepare_arrays, reduce_queries, strict_sql
- теперь логирование выгрузки в realtime (более подробное в debug_mode = 1)
- сильно уменьшено количество SQL запросов (для супер эффекта поставьте reduce_queries = 1)
- улучшено отслеживание изменений для формирования прайс-листа
- добавлен рекурсивный выбор категорий в дереве (для больших структур must have)
- добавлена возможность выбирать родительские значения и ТВ поля к товарам
- добавлены события ym2OnOffersQuery, ym2OnCategoriesQuery для изменения запросов для товаров и категорий
- также добавлены события для добавления значений к товару или категории, перед и после генерации прайс-листа

1.0.3-beta (07.04.2021)
==============
- Исправлена ошибка с галерей ms2
- Поправлено обновление и удаление компонента

1.0.2-beta (18.03.2021)
==============
- Убраны предупреждения объектов при просмотре лога действий

1.0.1-beta (17.03.2021)
==============
- Добавил режим формирования файла по-умолчанию
- Мелкие исправления в интерфейсе

1.0.0-beta
==============
- Абсолютно новая версия компонента для выгрузки прайс-листов на Яндекс Маркет и не только
- Подробнее в описании на ModStore https://modstore.pro/packages/import-and-export/yandcexmarket2
',
    'license' => 'GNU GENERAL PUBLIC LICENSE
   Version 2, June 1991
--------------------------

Copyright (C) 1989, 1991 Free Software Foundation, Inc.
59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Everyone is permitted to copy and distribute verbatim copies
of this license document, but changing it is not allowed.

Preamble
--------

  The licenses for most software are designed to take away your
freedom to share and change it.  By contrast, the GNU General Public
License is intended to guarantee your freedom to share and change free
software--to make sure the software is free for all its users.  This
General Public License applies to most of the Free Software
Foundation\'s software and to any other program whose authors commit to
using it.  (Some other Free Software Foundation software is covered by
the GNU Library General Public License instead.)  You can apply it to
your programs, too.

  When we speak of free software, we are referring to freedom, not
price.  Our General Public Licenses are designed to make sure that you
have the freedom to distribute copies of free software (and charge for
this service if you wish), that you receive source code or can get it
if you want it, that you can change the software or use pieces of it
in new free programs; and that you know you can do these things.

  To protect your rights, we need to make restrictions that forbid
anyone to deny you these rights or to ask you to surrender the rights.
These restrictions translate to certain responsibilities for you if you
distribute copies of the software, or if you modify it.

  For example, if you distribute copies of such a program, whether
gratis or for a fee, you must give the recipients all the rights that
you have.  You must make sure that they, too, receive or can get the
source code.  And you must show them these terms so they know their
rights.

  We protect your rights with two steps: (1) copyright the software, and
(2) offer you this license which gives you legal permission to copy,
distribute and/or modify the software.

  Also, for each author\'s protection and ours, we want to make certain
that everyone understands that there is no warranty for this free
software.  If the software is modified by someone else and passed on, we
want its recipients to know that what they have is not the original, so
that any problems introduced by others will not reflect on the original
authors\' reputations.

  Finally, any free program is threatened constantly by software
patents.  We wish to avoid the danger that redistributors of a free
program will individually obtain patent licenses, in effect making the
program proprietary.  To prevent this, we have made it clear that any
patent must be licensed for everyone\'s free use or not licensed at all.

  The precise terms and conditions for copying, distribution and
modification follow.


GNU GENERAL PUBLIC LICENSE
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
---------------------------------------------------------------

  0. This License applies to any program or other work which contains
a notice placed by the copyright holder saying it may be distributed
under the terms of this General Public License.  The "Program", below,
refers to any such program or work, and a "work based on the Program"
means either the Program or any derivative work under copyright law:
that is to say, a work containing the Program or a portion of it,
either verbatim or with modifications and/or translated into another
language.  (Hereinafter, translation is included without limitation in
the term "modification".)  Each licensee is addressed as "you".

Activities other than copying, distribution and modification are not
covered by this License; they are outside its scope.  The act of
running the Program is not restricted, and the output from the Program
is covered only if its contents constitute a work based on the
Program (independent of having been made by running the Program).
Whether that is true depends on what the Program does.

  1. You may copy and distribute verbatim copies of the Program\'s
source code as you receive it, in any medium, provided that you
conspicuously and appropriately publish on each copy an appropriate
copyright notice and disclaimer of warranty; keep intact all the
notices that refer to this License and to the absence of any warranty;
and give any other recipients of the Program a copy of this License
along with the Program.

You may charge a fee for the physical act of transferring a copy, and
you may at your option offer warranty protection in exchange for a fee.

  2. You may modify your copy or copies of the Program or any portion
of it, thus forming a work based on the Program, and copy and
distribute such modifications or work under the terms of Section 1
above, provided that you also meet all of these conditions:

    a) You must cause the modified files to carry prominent notices
    stating that you changed the files and the date of any change.

    b) You must cause any work that you distribute or publish, that in
    whole or in part contains or is derived from the Program or any
    part thereof, to be licensed as a whole at no charge to all third
    parties under the terms of this License.

    c) If the modified program normally reads commands interactively
    when run, you must cause it, when started running for such
    interactive use in the most ordinary way, to print or display an
    announcement including an appropriate copyright notice and a
    notice that there is no warranty (or else, saying that you provide
    a warranty) and that users may redistribute the program under
    these conditions, and telling the user how to view a copy of this
    License.  (Exception: if the Program itself is interactive but
    does not normally print such an announcement, your work based on
    the Program is not required to print an announcement.)

These requirements apply to the modified work as a whole.  If
identifiable sections of that work are not derived from the Program,
and can be reasonably considered independent and separate works in
themselves, then this License, and its terms, do not apply to those
sections when you distribute them as separate works.  But when you
distribute the same sections as part of a whole which is a work based
on the Program, the distribution of the whole must be on the terms of
this License, whose permissions for other licensees extend to the
entire whole, and thus to each and every part regardless of who wrote it.

Thus, it is not the intent of this section to claim rights or contest
your rights to work written entirely by you; rather, the intent is to
exercise the right to control the distribution of derivative or
collective works based on the Program.

In addition, mere aggregation of another work not based on the Program
with the Program (or with a work based on the Program) on a volume of
a storage or distribution medium does not bring the other work under
the scope of this License.

  3. You may copy and distribute the Program (or a work based on it,
under Section 2) in object code or executable form under the terms of
Sections 1 and 2 above provided that you also do one of the following:

    a) Accompany it with the complete corresponding machine-readable
    source code, which must be distributed under the terms of Sections
    1 and 2 above on a medium customarily used for software interchange; or,

    b) Accompany it with a written offer, valid for at least three
    years, to give any third party, for a charge no more than your
    cost of physically performing source distribution, a complete
    machine-readable copy of the corresponding source code, to be
    distributed under the terms of Sections 1 and 2 above on a medium
    customarily used for software interchange; or,

    c) Accompany it with the information you received as to the offer
    to distribute corresponding source code.  (This alternative is
    allowed only for noncommercial distribution and only if you
    received the program in object code or executable form with such
    an offer, in accord with Subsection b above.)

The source code for a work means the preferred form of the work for
making modifications to it.  For an executable work, complete source
code means all the source code for all modules it contains, plus any
associated interface definition files, plus the scripts used to
control compilation and installation of the executable.  However, as a
special exception, the source code distributed need not include
anything that is normally distributed (in either source or binary
form) with the major components (compiler, kernel, and so on) of the
operating system on which the executable runs, unless that component
itself accompanies the executable.

If distribution of executable or object code is made by offering
access to copy from a designated place, then offering equivalent
access to copy the source code from the same place counts as
distribution of the source code, even though third parties are not
compelled to copy the source along with the object code.

  4. You may not copy, modify, sublicense, or distribute the Program
except as expressly provided under this License.  Any attempt
otherwise to copy, modify, sublicense or distribute the Program is
void, and will automatically terminate your rights under this License.
However, parties who have received copies, or rights, from you under
this License will not have their licenses terminated so long as such
parties remain in full compliance.

  5. You are not required to accept this License, since you have not
signed it.  However, nothing else grants you permission to modify or
distribute the Program or its derivative works.  These actions are
prohibited by law if you do not accept this License.  Therefore, by
modifying or distributing the Program (or any work based on the
Program), you indicate your acceptance of this License to do so, and
all its terms and conditions for copying, distributing or modifying
the Program or works based on it.

  6. Each time you redistribute the Program (or any work based on the
Program), the recipient automatically receives a license from the
original licensor to copy, distribute or modify the Program subject to
these terms and conditions.  You may not impose any further
restrictions on the recipients\' exercise of the rights granted herein.
You are not responsible for enforcing compliance by third parties to
this License.

  7. If, as a consequence of a court judgment or allegation of patent
infringement or for any other reason (not limited to patent issues),
conditions are imposed on you (whether by court order, agreement or
otherwise) that contradict the conditions of this License, they do not
excuse you from the conditions of this License.  If you cannot
distribute so as to satisfy simultaneously your obligations under this
License and any other pertinent obligations, then as a consequence you
may not distribute the Program at all.  For example, if a patent
license would not permit royalty-free redistribution of the Program by
all those who receive copies directly or indirectly through you, then
the only way you could satisfy both it and this License would be to
refrain entirely from distribution of the Program.

If any portion of this section is held invalid or unenforceable under
any particular circumstance, the balance of the section is intended to
apply and the section as a whole is intended to apply in other
circumstances.

It is not the purpose of this section to induce you to infringe any
patents or other property right claims or to contest validity of any
such claims; this section has the sole purpose of protecting the
integrity of the free software distribution system, which is
implemented by public license practices.  Many people have made
generous contributions to the wide range of software distributed
through that system in reliance on consistent application of that
system; it is up to the author/donor to decide if he or she is willing
to distribute software through any other system and a licensee cannot
impose that choice.

This section is intended to make thoroughly clear what is believed to
be a consequence of the rest of this License.

  8. If the distribution and/or use of the Program is restricted in
certain countries either by patents or by copyrighted interfaces, the
original copyright holder who places the Program under this License
may add an explicit geographical distribution limitation excluding
those countries, so that distribution is permitted only in or among
countries not thus excluded.  In such case, this License incorporates
the limitation as if written in the body of this License.

  9. The Free Software Foundation may publish revised and/or new versions
of the General Public License from time to time.  Such new versions will
be similar in spirit to the present version, but may differ in detail to
address new problems or concerns.

Each version is given a distinguishing version number.  If the Program
specifies a version number of this License which applies to it and "any
later version", you have the option of following the terms and conditions
either of that version or of any later version published by the Free
Software Foundation.  If the Program does not specify a version number of
this License, you may choose any version ever published by the Free Software
Foundation.

  10. If you wish to incorporate parts of the Program into other free
programs whose distribution conditions are different, write to the author
to ask for permission.  For software which is copyrighted by the Free
Software Foundation, write to the Free Software Foundation; we sometimes
make exceptions for this.  Our decision will be guided by the two goals
of preserving the free status of all derivatives of our free software and
of promoting the sharing and reuse of software generally.

NO WARRANTY
-----------

  11. BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY
FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW.  EXCEPT WHEN
OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES
PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED
OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.  THE ENTIRE RISK AS
TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU.  SHOULD THE
PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING,
REPAIR OR CORRECTION.

  12. IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING
WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MAY MODIFY AND/OR
REDISTRIBUTE THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES,
INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING
OUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED
TO LOSS OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY
YOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER
PROGRAMS), EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE
POSSIBILITY OF SUCH DAMAGES.

---------------------------
END OF TERMS AND CONDITIONS',
    'readme' => '--------------------
YandexMarket2
--------------------
Author: Evgeniy Sheronov <webrush@bk.ru>
--------------------

Export of offers in XML to Yandex Market and not only',
  ),
  'manifest-vehicles' => 
  array (
    0 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOFileVehicle',
      'class' => 'xPDOFileVehicle',
      'guid' => '694e345b1dccda8df818a53ac6867a8a',
      'native_key' => NULL,
      'filename' => 'xPDOFileVehicle/c4cd7ede252527acbf7b4f65cba501af.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    1 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOScriptVehicle',
      'class' => 'xPDOScriptVehicle',
      'guid' => 'cd40a347b9671a5bba9d189c4396e532',
      'native_key' => NULL,
      'filename' => 'xPDOScriptVehicle/eadfe18cce20844bbac3e395974eca6a.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    2 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modNamespace',
      'guid' => 'f3188b54877c2d9ecd670d930e8b1504',
      'native_key' => 'yandexmarket2',
      'filename' => 'modNamespace/fa83481424b45a2742ac0e2767c65b6d.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    3 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modEvent',
      'guid' => '8745adb16449ed8dedaeba93bfd72525',
      'native_key' => 'ym2OnBeforeQuery',
      'filename' => 'modEvent/1cfc6d5c162fe0feff808a5458c99faa.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    4 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modEvent',
      'guid' => 'dff52b2bb2a78f3d4981737d9fa76583',
      'native_key' => 'ym2OnBeforeWritingOffer',
      'filename' => 'modEvent/9e209da1a75dfe8f09283d70b34c12d9.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    5 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modEvent',
      'guid' => 'e3f5cf2e601b6ba79427d9b8790c1986',
      'native_key' => 'ym2OnBeforeWritingCategory',
      'filename' => 'modEvent/481add577216f950e5811e3fa2456120.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    6 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modEvent',
      'guid' => '6a9e11836d767f658fe2c68be4fe2505',
      'native_key' => 'ym2OnOffersQuery',
      'filename' => 'modEvent/af028afa278525a13bfd5fe30b77a7ae.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    7 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modEvent',
      'guid' => 'e64492723770fd290e5bf15c2740c883',
      'native_key' => 'ym2OnCategoriesQuery',
      'filename' => 'modEvent/c635328ad4655c460730db3fe2e945eb.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    8 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modEvent',
      'guid' => 'e0c4028456eb46eba037b74832d31082',
      'native_key' => 'ym2OnBeforePricelistGenerate',
      'filename' => 'modEvent/6683ca91ee59144c0bce8bbb4c8ebf59.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    9 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modEvent',
      'guid' => '87975e7298287e46ddf42f33ac1c3ff7',
      'native_key' => 'ym2OnAfterPricelistGenerate',
      'filename' => 'modEvent/e35b990471618bdb2e51c4b21fb4d92e.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    10 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modMenu',
      'guid' => 'eef472d653d11c674f6436fde8537cdd',
      'native_key' => 'yandexmarket2',
      'filename' => 'modMenu/2fa7e34ffae819260e9750633685b1df.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    11 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modSystemSetting',
      'guid' => '8e26e37f17509fd2acf3a93a085f003d',
      'native_key' => 'yandexmarket2_debug_mode',
      'filename' => 'modSystemSetting/4996792658cf1db0b8dd90b4692bd651.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    12 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'b0963fa3c366c77a56e3b4af50d80e94',
      'native_key' => 'yandexmarket2_strict_sql',
      'filename' => 'modSystemSetting/4f27023ef265f3fde73536752f21f27a.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    13 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'b098b6871664d72e5f465613e31d947e',
      'native_key' => 'yandexmarket2_reduce_queries',
      'filename' => 'modSystemSetting/09d81a89ab38198a16b899e906e37844.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    14 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'dd24a75a6565eef10d587e8672cc6622',
      'native_key' => 'yandexmarket2_prepare_arrays',
      'filename' => 'modSystemSetting/ad1bf3ea3b011d5942f9cdd5e5385279.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    15 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'a5b08498b0a1e6365e4310ea559d9774',
      'native_key' => 'yandexmarket2_images_url',
      'filename' => 'modSystemSetting/2b249b42b4ab262a634a5e839b537c04.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    16 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'be51681230aa8631bd834bf55bd07f12',
      'native_key' => 'yandexmarket2_site_url',
      'filename' => 'modSystemSetting/f67dbb1c55c26371944730db2c8db225.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    17 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modSystemSetting',
      'guid' => '18c7ac1f8831f459591d339c63ed452f',
      'native_key' => 'yandexmarket2_files_path',
      'filename' => 'modSystemSetting/241b22ccd83cfbef1095efcf24b5f5f1.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    18 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modSystemSetting',
      'guid' => '4585cf82f208183f34b2b74f26ecfb61',
      'native_key' => 'yandexmarket2_files_url',
      'filename' => 'modSystemSetting/3ff69fb0c1800792a6f2955e2a9d23d6.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    19 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'EncryptedVehicle',
      'class' => 'modCategory',
      'guid' => '29f574a541b1cbe95c50cf012ab5a5ad',
      'native_key' => 1,
      'filename' => 'modCategory/0acfc70f4b0128dac191981cbb8e1613.vehicle',
      'namespace' => 'yandexmarket2',
    ),
    20 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOScriptVehicle',
      'class' => 'xPDOScriptVehicle',
      'guid' => 'e25d41030e72539528e12ba89df5c9e3',
      'native_key' => 'e25d41030e72539528e12ba89df5c9e3',
      'filename' => 'xPDOScriptVehicle/40fb23af5a4afcdf92130bfe728fd3c4.vehicle',
      'namespace' => 'yandexmarket2',
    ),
  ),
);